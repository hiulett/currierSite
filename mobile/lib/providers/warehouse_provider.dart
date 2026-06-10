import 'package:flutter/material.dart';
import '../services/api_service.dart';

class ScannedItem {
  final String tracking;
  double weight;
  bool isSynced;

  ScannedItem({required this.tracking, this.weight = 1.0, this.isSynced = false});
}

class WarehouseProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();
  List<dynamic> _warehouses = [];
  int? _selectedWarehouseId;
  final List<ScannedItem> _scannedItems = [];
  bool _isSyncing = false;

  List<dynamic> get warehouses => _warehouses;
  int? get selectedWarehouseId => _selectedWarehouseId;
  List<ScannedItem> get scannedItems => _scannedItems;
  bool get isSyncing => _isSyncing;

  Future<void> fetchWarehouses() async {
    _warehouses = await _apiService.getWarehouses();
    if (_warehouses.isNotEmpty && _selectedWarehouseId == null) {
      _selectedWarehouseId = _warehouses[0]['id'];
    }
    notifyListeners();
  }

  void selectWarehouse(int id) {
    _selectedWarehouseId = id;
    notifyListeners();
  }

  void addTracking(String tracking) {
    // Avoid duplicates in current session
    if (_scannedItems.any((item) => item.tracking == tracking)) return;

    _scannedItems.insert(0, ScannedItem(tracking: tracking));
    notifyListeners();
  }

  void updateWeight(String tracking, double weight) {
    final index = _scannedItems.indexWhere((item) => item.tracking == tracking);
    if (index != -1) {
      _scannedItems[index].weight = weight;
      notifyListeners();
    }
  }

  void removeItem(String tracking) {
    _scannedItems.removeWhere((item) => item.tracking == tracking);
    notifyListeners();
  }

  Future<bool> syncBatch() async {
    if (_selectedWarehouseId == null || _scannedItems.isEmpty) return false;

    _isSyncing = true;
    notifyListeners();

    final itemsToSync = _scannedItems.where((item) => !item.isSynced).map((item) => {
      'tracking_number': item.tracking,
      'weight': item.weight,
    }).toList();

    if (itemsToSync.isEmpty) {
      _isSyncing = false;
      notifyListeners();
      return true;
    }

    final result = await _apiService.submitBulkReception(_selectedWarehouseId!, itemsToSync);

    if (result != null && result['success'] == true) {
      // Mark as synced or just clear list
      _scannedItems.clear();
      _isSyncing = false;
      notifyListeners();
      return true;
    }

    _isSyncing = false;
    notifyListeners();
    return false;
  }
}
