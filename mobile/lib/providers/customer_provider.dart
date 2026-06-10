import 'package:flutter/material.dart';
import '../services/api_service.dart';

class CustomerProvider with ChangeNotifier {
  final ApiService _apiService = ApiService();

  Map<String, dynamic>? _profile;
  List<dynamic> _packages = [];
  List<dynamic> _invoices = [];
  List<dynamic> _assistedPurchases = [];
  Map<String, dynamic>? _balance;
  bool _isLoading = false;

  Map<String, dynamic>? get profile => _profile;
  List<dynamic> get packages => _packages;
  List<dynamic> get invoices => _invoices;
  List<dynamic> get assistedPurchases => _assistedPurchases;
  Map<String, dynamic>? get balance => _balance;
  bool get isLoading => _isLoading;

  Future<void> refreshAll() async {
    _isLoading = true;
    notifyListeners();

    await Future.wait([
      fetchProfile(),
      fetchPackages(),
      fetchInvoices(),
      fetchBalance(),
      fetchAssistedPurchases(),
    ]);

    _isLoading = false;
    notifyListeners();
  }

  Future<void> fetchAssistedPurchases() async {
    _assistedPurchases = await _apiService.getAssistedPurchases();
    notifyListeners();
  }

  Future<bool> requestAssistedPurchase(String url, String description, double? price) async {
    final result = await _apiService.createAssistedPurchase(url, description, price);
    if (result != null) {
      await fetchAssistedPurchases();
      return true;
    }
    return false;
  }

  Future<void> fetchProfile() async {
    _profile = await _apiService.getCustomerProfile();
    notifyListeners();
  }

  Future<void> fetchPackages() async {
    _packages = await _apiService.getCustomerPackages();
    notifyListeners();
  }

  Future<void> fetchInvoices() async {
    _invoices = await _apiService.getCustomerInvoices();
    notifyListeners();
  }

  Future<void> fetchBalance() async {
    _balance = await _apiService.getCustomerBalance();
    notifyListeners();
  }

  Future<Map<String, dynamic>?> getPackageDetail(int id) async {
    return await _apiService.getPackageDetail(id);
  }
}
