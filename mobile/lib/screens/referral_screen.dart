import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';
import 'package:share_plus/share_plus.dart';

class ReferralScreen extends StatelessWidget {
  const ReferralScreen({super.key});

  @override
  Widget build(BuildContext context) {
    final provider = Provider.of<CustomerProvider>(context);
    final profile = provider.profile;
    final referralCode = profile?['customer']?['referral_code'] ?? '...';
    final referralLink = profile?['referral_link'] ?? '...';

    return Scaffold(
      appBar: AppBar(title: const Text("Refiere y Gana")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(32),
        child: Column(
          children: [
            const Icon(Icons.group_add, size: 100, color: Colors.blue),
            const SizedBox(height: 24),
            const Text(
              "Gana puntos por cada amigo",
              textAlign: TextAlign.center,
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 16),
            const Text(
              "Comparte tu código único. Cuando tus amigos se registren y traigan su primer paquete, ambos ganarán crédito para su próximo envío.",
              textAlign: TextAlign.center,
              style: TextStyle(color: Colors.grey, fontSize: 16),
            ),
            const SizedBox(height: 48),

            Container(
              padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 20),
              decoration: BoxDecoration(
                color: Colors.grey.shade100,
                borderRadius: BorderRadius.circular(16),
                border: Border.all(color: Colors.grey.shade300),
              ),
              child: Column(
                children: [
                  const Text("TU CÓDIGO", style: TextStyle(letterSpacing: 1.5, fontSize: 12, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 8),
                  Text(referralCode, style: const TextStyle(fontSize: 32, fontWeight: FontWeight.black, color: Colors.blue)),
                ],
              ),
            ),

            const SizedBox(height: 32),

            SizedBox(
              width: double.infinity,
              height: 54,
              child: ElevatedButton.icon(
                onPressed: () {
                  Share.share("¡Usa mi código $referralCode para registrarte en LogySaaS y obtén descuentos en tus envíos! $referralLink");
                },
                icon: const Icon(Icons.share),
                label: const Text("COMPARTIR ENLACE", style: TextStyle(fontWeight: FontWeight.bold)),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.blue.shade800,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(12)),
                ),
              ),
            ),

            const SizedBox(height: 60),
            _buildStatRow("Amigos Referidos", "0"),
            const Divider(height: 32),
            _buildStatRow("Crédito Ganado", "\$0.00"),
          ],
        ),
      ),
    );
  }

  Widget _buildStatRow(String label, String value) {
    return Row(
      mainAxisAlignment: MainAxisAlignment.spaceBetween,
      children: [
        Text(label, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.w500)),
        Text(value, style: const TextStyle(fontSize: 18, fontWeight: FontWeight.bold, color: Colors.green)),
      ],
    );
  }
}
