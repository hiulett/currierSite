import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';

class InvoicesScreen extends StatelessWidget {
  const InvoicesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CustomerProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Mis Facturas", style: TextStyle(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
      ),
      body: provider.invoices.isEmpty
          ? const Center(child: Text("No tienes facturas generadas"))
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: provider.invoices.length,
              itemBuilder: (context, index) {
                final inv = provider.invoices[index];
                return _buildInvoiceCard(context, inv);
              },
            ),
    );
  }

  Widget _buildInvoiceCard(BuildContext context, dynamic inv) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: ListTile(
        contentPadding: const EdgeInsets.all(20),
        leading: CircleAvatar(
          backgroundColor: inv['status'] == 'paid' ? Colors.green.shade50 : Colors.red.shade50,
          child: Icon(
            inv['status'] == 'paid' ? Icons.check_circle_outline : Icons.pending_actions,
            color: inv['status'] == 'paid' ? Colors.green : Colors.red,
          ),
        ),
        title: Text("Factura #${inv['invoice_number'] ?? inv['id']}",
          style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text("${inv['created_at'].toString().split('T')[0]}"),
        trailing: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          crossAxisAlignment: CrossAxisAlignment.end,
          children: [
            Text("\$${inv['total']}", style: const TextStyle(fontWeight: FontWeight.w900, fontSize: 16)),
            Text(inv['status'] == 'paid' ? "PAGADA" : "PENDIENTE",
              style: TextStyle(color: inv['status'] == 'paid' ? Colors.green : Colors.red, fontSize: 10, fontWeight: FontWeight.bold)),
          ],
        ),
        onTap: () {
          // TODO: Open PDF
        },
      ),
    );
  }
}
