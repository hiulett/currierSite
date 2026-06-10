import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';
import '../providers/auth_provider.dart';
import 'package:url_launcher/url_launcher.dart';

class ProfileScreen extends StatelessWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CustomerProvider>(context);

    return Scaffold(
      appBar: AppBar(title: const Text("Perfil")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          children: [
            const CircleAvatar(
              radius: 50,
              backgroundColor: Colors.blue,
              child: Icon(Icons.person, size: 50, color: Colors.white),
            ),
            const SizedBox(height: 16),
            Text(provider.profile?['user']?['name'] ?? 'N/A',
              style: const TextStyle(fontSize: 22, fontWeight: FontWeight.bold)),
            Text(provider.profile?['user']?['email'] ?? 'N/A',
              style: const TextStyle(color: Colors.grey)),

            const SizedBox(height: 40),

            _buildLockerAddress(provider.profile),

            const SizedBox(height: 32),

            _buildMenuOption(
              icon: Icons.chat_bubble_outline,
              label: "Soporte vía WhatsApp",
              color: Colors.green,
              onTap: () => _launchWhatsApp(),
            ),
            _buildMenuOption(
              icon: Icons.logout,
              label: "Cerrar Sesión",
              color: Colors.red,
              onTap: () {
                Provider.of<AuthProvider>(context, listen: false).logout();
                Navigator.pushReplacementNamed(context, '/login');
              },
            ),
          ],
        ),
      ),
    );
  }

  Widget _buildLockerAddress(Map<String, dynamic>? profile) {
    final locker = profile?['locker'];
    if (locker == null) return const SizedBox();

    return Container(
      width: double.infinity,
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(
        color: Colors.blue.shade50,
        borderRadius: BorderRadius.circular(20),
        border: Border.all(color: Colors.blue.shade100),
      ),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          const Row(
            children: [
              Icon(Icons.location_on, color: Colors.blue),
              SizedBox(width: 8),
              Text("Tu Dirección en Miami", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 16, color: Colors.blue)),
            ],
          ),
          const SizedBox(height: 16),
          _buildAddressLine("Nombre:", profile?['user']?['name']),
          _buildAddressLine("Address 1:", locker['address']),
          _buildAddressLine("Address 2:", profile?['customer']?['box_number'] ?? 'N/A'),
          _buildAddressLine("City:", locker['city']),
          _buildAddressLine("State:", locker['state']),
          _buildAddressLine("Zip:", locker['zip_code']),
        ],
      ),
    );
  }

  Widget _buildAddressLine(String label, String? value) {
    return Padding(
      padding: const EdgeInsets.only(bottom: 8.0),
      child: Row(
        children: [
          Text(label, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 12)),
          const SizedBox(width: 8),
          Expanded(child: Text(value ?? 'N/A', style: const TextStyle(fontSize: 12))),
        ],
      ),
    );
  }

  Widget _buildMenuOption({required IconData icon, required String label, required Color color, required VoidCallback onTap}) {
    return ListTile(
      leading: Icon(icon, color: color),
      title: Text(label, style: const TextStyle(fontWeight: FontWeight.w600)),
      trailing: const Icon(Icons.chevron_right, size: 20),
      onTap: onTap,
    );
  }

  void _launchWhatsApp() async {
    const url = "https://wa.me/50760000000"; // TODO: get from tenant settings
    if (await canLaunchUrl(Uri.parse(url))) {
      await launchUrl(Uri.parse(url), mode: LaunchMode.externalApplication);
    }
  }
}
