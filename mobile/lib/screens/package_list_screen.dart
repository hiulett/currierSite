import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';
import 'package_detail_screen.dart';

class PackageListScreen extends StatelessWidget {
  const PackageListScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CustomerProvider>(context);

    return Scaffold(
      appBar: AppBar(
        title: const Text("Mis Paquetes", style: TextStyle(fontWeight: FontWeight.bold)),
        backgroundColor: Colors.white,
        elevation: 0,
      ),
      body: provider.packages.isEmpty
          ? const Center(child: Text("No tienes paquetes"))
          : ListView.builder(
              padding: const EdgeInsets.all(16),
              itemCount: provider.packages.length,
              itemBuilder: (context, index) {
                final pkg = provider.packages[index];
                return _buildPackageCard(context, pkg);
              },
            ),
    );
  }

  Widget _buildPackageCard(BuildContext context, dynamic pkg) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      elevation: 1,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(20), side: BorderSide(color: Colors.grey.shade200)),
      child: InkWell(
        borderRadius: BorderRadius.circular(20),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(builder: (context) => PackageDetailScreen(packageId: pkg['id'])),
          );
        },
        child: Padding(
          padding: const EdgeInsets.all(20),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Row(
                mainAxisAlignment: MainAxisAlignment.spaceBetween,
                children: [
                  Text(pkg['tracking_number'] ?? 'N/A',
                    style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16, fontFamily: 'monospace')),
                  _buildStatusChip(pkg['status']),
                ],
              ),
              const SizedBox(height: 12),
              Text(pkg['description'] ?? 'Sin descripción', style: TextStyle(color: Colors.grey.shade700)),
              const Divider(height: 32),
              Row(
                children: [
                  _buildInfo(Icons.monitor_weight_outlined, "${pkg['weight'] ?? '0'} lbs"),
                  const SizedBox(width: 24),
                  _buildInfo(Icons.warehouse_outlined, pkg['warehouse']?['name'] ?? 'N/A'),
                  const Spacer(),
                  TextButton.icon(
                    onPressed: () {
                      Navigator.push(
                        context,
                        MaterialPageRoute(builder: (context) => PackageDetailScreen(packageId: pkg['id'])),
                      );
                    },
                    icon: const Icon(Icons.location_searching, size: 18),
                    label: const Text("RASTREAR"),
                  )
                ],
              )
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildInfo(IconData icon, String value) {
    return Row(
      children: [
        Icon(icon, size: 16, color: Colors.grey),
        const SizedBox(width: 8),
        Text(value, style: const TextStyle(fontWeight: FontWeight.w600, fontSize: 13)),
      ],
    );
  }

  Widget _buildStatusChip(String? status) {
    Color color = Colors.grey;
    String label = status ?? 'Desconocido';
    switch(status) {
      case 'received_miami': color = Colors.orange; label = "Recibido en Miami"; break;
      case 'in_transit': color = Colors.blue; label = "En Camino"; break;
      case 'arrived': color = Colors.teal; label = "Listo para retirar"; break;
      case 'delivered': color = Colors.green; label = "Entregado"; break;
    }

    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
      decoration: BoxDecoration(color: color.withOpacity(0.1), borderRadius: BorderRadius.circular(30)),
      child: Text(label, style: TextStyle(color: color, fontSize: 11, fontWeight: FontWeight.bold)),
    );
  }
}
