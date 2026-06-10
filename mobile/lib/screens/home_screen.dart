import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/auth_provider.dart';
import 'scanner_screen.dart';

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
      body: Center(
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Text("Bienvenido, ${user?['name'] ?? 'Usuario'}", style: const TextStyle(fontSize: 20)),
            const SizedBox(height: 48),
            Card(
              margin: const EdgeInsets.symmetric(horizontal: 24),
              child: ListTile(
                leading: const Icon(Icons.qr_code_scanner, size: 40, color: Colors.blue),
                title: const Text("Recepción de Bodega"),
                subtitle: const Text("Escanea trackings para recibir carga"),
                onTap: () {
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => const ScannerScreen()),
                  );
                },
              ),
            ),
          ],
        ),
      ),
    );
  }
}
