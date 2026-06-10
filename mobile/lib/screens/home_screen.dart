import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import 'scanner_screen.dart';

import 'bulk_reception_screen.dart';

class HomeScreen extends StatelessWidget {
  const HomeScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final user = Provider.of<AuthProvider>(context).user;

    return Scaffold(
      appBar: AppBar(
        title: const Text("LogySaaS Dashboard"),
        actions: [
          IconButton(
            icon: const Icon(Icons.logout),
            onPressed: () {
              Provider.of<AuthProvider>(context, listen: false).logout();
              Navigator.pushReplacementNamed(context, '/login');
            },
          )
        ],
      ),
      body: SingleChildScrollView(
        child: Padding(
          padding: const EdgeInsets.symmetric(vertical: 24.0),
          child: Column(
            children: [
              Text("Bienvenido, ${user?['name'] ?? 'Usuario'}", style: const TextStyle(fontSize: 20, fontWeight: FontWeight.bold)),
              const SizedBox(height: 32),

              _buildMenuCard(
                context,
                Icons.qr_code_scanner,
                "Recepción Unitaria",
                "Escaneo con confirmación individual",
                () => Navigator.push(context, MaterialPageRoute(builder: (context) => const ScannerScreen()))
              ),

              const SizedBox(height: 16),

              _buildMenuCard(
                context,
                Icons.batch_prediction,
                "Recepción Masiva",
                "Escaneo continuo por lotes (BETA)",
                () => Navigator.push(context, MaterialPageRoute(builder: (context) => const BulkReceptionScreen()))
              ),

              const SizedBox(height: 16),

              _buildMenuCard(
                context,
                Icons.local_shipping,
                "Última Milla",
                "Gestión de entregas y firmas",
                null // TODO: implement
              ),
            ],
          ),
        ),
      ),
    );
  }

  Widget _buildMenuCard(BuildContext context, IconData icon, String title, String subtitle, VoidCallback? onTap) {
    return Card(
      margin: const EdgeInsets.symmetric(horizontal: 24),
      elevation: 2,
      shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(16)),
      child: ListTile(
        enabled: onTap != null,
        leading: Icon(icon, size: 40, color: onTap != null ? Colors.blue : Colors.grey),
        title: Text(title, style: const TextStyle(fontWeight: FontWeight.bold)),
        subtitle: Text(subtitle),
        trailing: const Icon(Icons.chevron_right),
        onTap: onTap,
      ),
    );
  }
}
