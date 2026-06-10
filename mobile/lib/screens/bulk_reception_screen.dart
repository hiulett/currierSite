import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../providers/warehouse_provider.dart';

class BulkReceptionScreen extends StatefulWidget {
  const BulkReceptionScreen({super.key});

  @override
  _BulkReceptionScreenState createState() => _BulkReceptionScreenState();
}

class _BulkReceptionScreenState extends State<BulkReceptionScreen> {
  bool _isScannerActive = true;

  @override
  void initState() {
    super.initState();
    Future.microtask(() =>
      Provider.of<WarehouseProvider>(context, listen: false).fetchWarehouses()
    );
  }

  @override
  Widget build(BuildContext context) {
    final warehouseProvider = Provider.of<WarehouseProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Recepción Masiva"),
        actions: [
          IconButton(
            icon: Icon(_isScannerActive ? Icons.camera_alt : Icons.list),
            onPressed: () => setState(() => _isScannerActive = !_isScannerActive),
          )
        ],
      ),
      body: Column(
        children: [
          // Header: Warehouse Selector
          Container(
            padding: const EdgeInsets.all(16),
            color: Colors.blue.shade50,
            child: Row(
              children: [
                const Icon(Icons.warehouse, color: Colors.blue),
                const SizedBox(width: 12),
                Expanded(
                  child: DropdownButton<int>(
                    value: warehouseProvider.selectedWarehouseId,
                    isExpanded: true,
                    underline: const SizedBox(),
                    items: warehouseProvider.warehouses.map<DropdownMenuItem<int>>((wh) {
                      return DropdownMenuItem<int>(
                        value: wh['id'],
                        child: Text(wh['name'], style: const TextStyle(fontWeight: FontWeight.bold)),
                      );
                    }).toList(),
                    onChanged: (val) => warehouseProvider.selectWarehouse(val!),
                  ),
                ),
              ],
            ),
          ),

          // Main View: Scanner or List
          Expanded(
            child: _isScannerActive
              ? Stack(
                  children: [
                    MobileScanner(
                      onDetect: (capture) {
                        final List<Barcode> barcodes = capture.barcodes;
                        for (final barcode in barcodes) {
                          if (barcode.rawValue != null) {
                            warehouseProvider.addTracking(barcode.rawValue!);
                            // Optional: Add a sound or haptic feedback here
                          }
                        }
                      },
                    ),
                    // Scan Overlay
                    Center(
                      child: Container(
                        width: 250,
                        height: 250,
                        decoration: BoxDecoration(
                          border: Border.all(color: Colors.white, width: 2),
                          borderRadius: BorderRadius.circular(12),
                        ),
                      ),
                    ),
                    const Positioned(
                      bottom: 20,
                      left: 0,
                      right: 0,
                      child: Text(
                        "Escanea códigos continuamente",
                        textAlign: TextAlign.center,
                        style: TextStyle(color: Colors.white, backgroundColor: Colors.black54),
                      ),
                    ),
                  ],
                )
              : _buildScannedList(warehouseProvider),
          ),

          // Footer: Batch Stats and Sync Button
          Container(
            padding: const EdgeInsets.all(20),
            decoration: BoxDecoration(
              color: Colors.white,
              boxShadow: [BoxShadow(color: Colors.black12, blurRadius: 10, offset: const Offset(0, -5))],
            ),
            child: Row(
              children: [
                Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  mainAxisSize: MainAxisSize.min,
                  children: [
                    Text(
                      "${warehouseProvider.scannedItems.length} paquetes",
                      style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18),
                    ),
                    const Text("Listos para procesar"),
                  ],
                ),
                const Spacer(),
                ElevatedButton.icon(
                  onPressed: warehouseProvider.isSyncing || warehouseProvider.scannedItems.isEmpty
                    ? null
                    : () async {
                        final success = await warehouseProvider.syncBatch();
                        if (success) {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text("Lote procesado con éxito"))
                          );
                        } else {
                          ScaffoldMessenger.of(context).showSnackBar(
                            const SnackBar(content: Text("Error al sincronizar lote"))
                          );
                        }
                      },
                  icon: warehouseProvider.isSyncing
                    ? const SizedBox(width: 20, height: 20, child: CircularProgressIndicator(strokeWidth: 2))
                    : const Icon(Icons.cloud_upload),
                  label: const Text("SINCRONIZAR"),
                  style: ElevatedButton.styleFrom(
                    padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                    backgroundColor: Colors.green,
                    foregroundColor: Colors.white,
                  ),
                ),
              ],
            ),
          )
        ],
      ),
    );
  }

  Widget _buildScannedList(WarehouseProvider provider) {
    if (provider.scannedItems.isEmpty) {
      return const Center(child: Text("No hay paquetes escaneados aún"));
    }

    return ListView.builder(
      itemCount: provider.scannedItems.length,
      itemBuilder: (context, index) {
        final item = provider.scannedItems[index];
        return Card(
          margin: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
          child: ListTile(
            title: Text(item.tracking, style: const TextStyle(fontFamily: 'monospace', fontWeight: FontWeight.bold)),
            subtitle: Row(
              children: [
                const Text("Peso: "),
                SizedBox(
                  width: 60,
                  child: TextField(
                    keyboardType: const TextInputType.numberWithOptions(decimal: true),
                    decoration: const InputDecoration(isDense: true),
                    controller: TextEditingController(text: item.weight.toString()),
                    onChanged: (val) => provider.updateWeight(item.tracking, double.tryParse(val) ?? 1.0),
                  ),
                ),
                const Text(" lbs"),
              ],
            ),
            trailing: IconButton(
              icon: const Icon(Icons.delete, color: Colors.red),
              onPressed: () => provider.removeItem(item.tracking),
            ),
          ),
        );
      },
    );
  }
}
