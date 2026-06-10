import 'package:flutter/material.dart';
import 'package:mobile_scanner/mobile_scanner.dart';
import '../services/api_service.dart';

class ScannerScreen extends StatefulWidget {
  const ScannerScreen({super.key});

  @override
  _ScannerScreenState createState() => _ScannerScreenState();
}

class _ScannerScreenState extends State<ScannerScreen> {
  final ApiService _apiService = ApiService();
  bool _isProcessing = false;

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Escáner de Bodega")),
      body: MobileScanner(
        onDetect: (capture) async {
          if (_isProcessing) return;

          final List<Barcode> barcodes = capture.barcodes;
          for (final barcode in barcodes) {
            if (barcode.rawValue != null) {
              setState(() => _isProcessing = true);
              _handleScan(barcode.rawValue!);
              break;
            }
          }
        },
      ),
    );
  }

  Future<void> _handleScan(String tracking) async {
    final result = await _apiService.scanPackage(tracking);

    if (!mounted) return;

    if (result != null) {
      showModalBottomSheet(
        context: context,
        builder: (context) => Container(
          padding: const EdgeInsets.all(24),
          child: Column(
            mainAxisSize: MainAxisSize.min,
            children: [
              Text("Tracking: $tracking", style: const TextStyle(fontWeight: FontWeight.bold)),
              const SizedBox(height: 16),
              Text(result['message']),
              const SizedBox(height: 24),
              ElevatedButton(
                onPressed: () {
                  Navigator.pop(context);
                  setState(() => _isProcessing = false);
                },
                child: const Text("Continuar"),
              )
            ],
          ),
        ),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Error al procesar escaneo")),
      );
      setState(() => _isProcessing = false);
    }
  }
}
