import 'package:flutter/material.dart';
import 'package:signature/signature.dart';
import 'dart:typed_data';

class DeliveryScreen extends StatefulWidget {
  const DeliveryScreen({super.key});

  @override
  _DeliveryScreenState createState() => _DeliveryScreenState();
}

class _DeliveryScreenState extends State<DeliveryScreen> {
  final SignatureController _controller = SignatureController(
    penStrokeWidth: 3,
    penColor: Colors.black,
    exportBackgroundColor: Colors.white,
  );

  bool _isSigned = false;

  @override
  void initState() {
    super.initState();
    _controller.onDrawEnd = () => setState(() => _isSigned = true);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Prueba de Entrega")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text("Datos del Recibo", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            const TextField(
              decoration: InputDecoration(labelText: "Nombre de quien recibe", border: OutlineInputBorder()),
            ),
            const SizedBox(height: 24),
            const Text("Firma Digital", style: TextStyle(fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            Container(
              decoration: BoxDecoration(
                border: Border.all(color: Colors.grey),
                borderRadius: BorderRadius.circular(12),
              ),
              child: ClipRRect(
                borderRadius: BorderRadius.circular(12),
                child: Signature(
                  controller: _controller,
                  height: 200,
                  backgroundColor: Colors.grey.shade50,
                ),
              ),
            ),
            Row(
              mainAxisAlignment: MainAxisAlignment.end,
              children: [
                TextButton(
                  onPressed: () {
                    _controller.clear();
                    setState(() => _isSigned = false);
                  },
                  child: const Text("Limpiar Firma")
                ),
              ],
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              height: 54,
              child: ElevatedButton(
                onPressed: !_isSigned ? null : () async {
                  // Uint8List? signature = await _controller.toPngBytes();
                  // TODO: Submit to API
                  ScaffoldMessenger.of(context).showSnackBar(
                    const SnackBar(content: Text("Entrega registrada con éxito"))
                  );
                  Navigator.pop(context);
                },
                style: ElevatedButton.styleFrom(backgroundColor: Colors.blue.shade800, foregroundColor: Colors.white),
                child: const Text("CONFIRMAR ENTREGA"),
              ),
            )
          ],
        ),
      ),
    );
  }
}
