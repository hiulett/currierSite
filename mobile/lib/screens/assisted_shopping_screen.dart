import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';

class AssistedShoppingScreen extends StatefulWidget {
  const AssistedShoppingScreen({super.key});

  @override
  _AssistedShoppingScreenState createState() => _AssistedShoppingScreenState();
}

class _AssistedShoppingScreenState extends State<AssistedShoppingScreen> {
  final _urlController = TextEditingController();
  final _descController = TextEditingController();
  final _priceController = TextEditingController();
  bool _isSubmitting = false;

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CustomerProvider>(context);

    return Scaffold(
      appBar: AppBar(title: const Text("Compras Asistidas")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text(
              "¿Quieres que compremos por ti?",
              style: TextStyle(fontSize: 20, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 8),
            const Text(
              "Pega el link de Amazon, eBay o cualquier tienda y nosotros nos encargamos.",
              style: TextStyle(color: Colors.grey),
            ),
            const SizedBox(height: 32),
            TextField(
              controller: _urlController,
              decoration: const InputDecoration(
                labelText: "Link del Producto",
                hintText: "https://amazon.com/...",
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.link),
              ),
            ),
            const SizedBox(height: 20),
            TextField(
              controller: _priceController,
              keyboardType: TextInputType.number,
              decoration: const InputDecoration(
                labelText: "Precio Estimado (USD)",
                border: OutlineInputBorder(),
                prefixIcon: Icon(Icons.attach_money),
              ),
            ),
            const SizedBox(height: 20),
            TextField(
              controller: _descController,
              maxLines: 3,
              decoration: const InputDecoration(
                labelText: "Notas Adicionales (Talla, Color, etc.)",
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              height: 54,
              child: ElevatedButton(
                onPressed: _isSubmitting ? null : _submit,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.orange.shade700,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
                child: _isSubmitting
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text("SOLICITAR COTIZACIÓN", style: TextStyle(fontWeight: FontWeight.bold)),
              ),
            ),

            const SizedBox(height: 48),
            const Text("Mis Solicitudes", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),

            if (provider.assistedPurchases.isEmpty)
              const Center(child: Text("No tienes solicitudes aún"))
            else
              ListView.builder(
                shrinkWrap: true,
                physics: const NeverScrollableScrollPhysics(),
                itemCount: provider.assistedPurchases.length,
                itemBuilder: (context, index) {
                  final item = provider.assistedPurchases[index];
                  return Card(
                    margin: const EdgeInsets.only(bottom: 12),
                    child: ListTile(
                      title: Text(item['url'], maxLines: 1, overflow: TextOverflow.ellipsis, style: const TextStyle(fontSize: 14)),
                      subtitle: Text("Estado: ${item['status'].toString().toUpperCase()}"),
                      trailing: Text("\$${item['estimated_price'] ?? '0.00'}", style: const TextStyle(fontWeight: FontWeight.bold)),
                    ),
                  );
                },
              ),
          ],
        ),
      ),
    );
  }

  Future<void> _submit() async {
    if (_urlController.text.isEmpty) return;

    setState(() => _isSubmitting = true);
    final provider = Provider.of<CustomerProvider>(context, listen: false);

    final success = await provider.requestAssistedPurchase(
      _urlController.text,
      _descController.text,
      double.tryParse(_priceController.text)
    );

    setState(() => _isSubmitting = false);

    if (success) {
      _urlController.clear();
      _descController.clear();
      _priceController.clear();
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Solicitud enviada con éxito")),
      );
    } else {
      ScaffoldMessenger.of(context).showSnackBar(
        const SnackBar(content: Text("Error al enviar solicitud")),
      );
    }
  }
}
