import 'package:flutter/material.dart';
import 'package:provider/provider.dart';
import '../providers/customer_provider.dart';
import 'package:intl/intl.dart';

class PackageDetailScreen extends StatefulWidget {
  final int packageId;
  const PackageDetailScreen({super.key, required this.packageId});

  @override
  _PackageDetailScreenState createState() => _PackageDetailScreenState();
}

class _PackageDetailScreenState extends State<PackageDetailScreen> {
  Map<String, dynamic>? _package;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    final provider = Provider.of<CustomerProvider>(context, listen: false);
    final data = await provider.getPackageDetail(widget.packageId);
    if (mounted) {
      setState(() {
        _package = data;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: const Text("Detalle de Envío")),
      body: _isLoading
        ? const Center(child: CircularProgressIndicator())
        : _package == null
          ? const Center(child: Text("No se encontró la información"))
          : SingleChildScrollView(
              padding: const EdgeInsets.all(24),
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  _buildHeader(),
                  const SizedBox(height: 40),
                  const Text("Línea de Tiempo", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
                  const SizedBox(height: 24),
                  _buildTimeline(),
                ],
              ),
            ),
    );
  }

  Widget _buildHeader() {
    return Container(
      padding: const EdgeInsets.all(24),
      decoration: BoxDecoration(
        color: Colors.white,
        borderRadius: BorderRadius.circular(24),
        boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.05), blurRadius: 10)],
      ),
      child: Column(
        children: [
          Row(
            children: [
              const CircleAvatar(backgroundColor: Colors.blue, child: Icon(Icons.inventory_2, color: Colors.white)),
              const SizedBox(width: 16),
              Expanded(
                child: Column(
                  crossAxisAlignment: CrossAxisAlignment.start,
                  children: [
                    Text(_package?['tracking_number'] ?? 'N/A', style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
                    Text(_package?['description'] ?? 'Sin descripción', style: const TextStyle(color: Colors.grey)),
                  ],
                ),
              )
            ],
          ),
          const Divider(height: 40),
          Row(
            mainAxisAlignment: MainAxisAlignment.spaceAround,
            children: [
              _buildMetric("PESO", "${_package?['weight']} lbs"),
              _buildMetric("VALOR", "\$${_package?['declared_value'] ?? '0.00'}"),
              _buildMetric("BODEGA", _package?['warehouse']?['code'] ?? 'N/A'),
            ],
          )
        ],
      ),
    );
  }

  Widget _buildMetric(String label, String value) {
    return Column(
      children: [
        Text(label, style: const TextStyle(color: Colors.grey, fontSize: 10, fontWeight: FontWeight.bold, letterSpacing: 1.2)),
        const SizedBox(height: 4),
        Text(value, style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
      ],
    );
  }

  Widget _buildTimeline() {
    final List events = _package?['tracking_events'] ?? [];
    if (events.isEmpty) return const Text("No hay eventos registrados");

    return ListView.builder(
      shrinkWrap: true,
      physics: const NeverScrollableScrollPhysics(),
      itemCount: events.length,
      itemBuilder: (context, index) {
        final event = events[index];
        final bool isLast = index == events.length - 1;
        final DateTime date = DateTime.parse(event['created_at']);

        return Row(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Column(
              children: [
                Container(
                  width: 20,
                  height: 20,
                  decoration: BoxDecoration(
                    color: index == 0 ? Colors.blue : Colors.grey.shade300,
                    shape: BoxShape.circle,
                    border: Border.all(color: Colors.white, width: 3),
                    boxShadow: index == 0 ? [BoxShadow(color: Colors.blue.withOpacity(0.4), blurRadius: 6)] : [],
                  ),
                ),
                if (!isLast)
                  Container(width: 2, height: 60, color: Colors.grey.shade200),
              ],
            ),
            const SizedBox(width: 20),
            Expanded(
              child: Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(_getStatusLabel(event['status']),
                    style: TextStyle(fontWeight: FontWeight.bold, color: index == 0 ? Colors.black : Colors.grey.shade600)),
                  Text(event['location'] ?? 'Ubicación no especificada', style: TextStyle(fontSize: 12, color: Colors.grey.shade500)),
                  const SizedBox(height: 4),
                  Text(DateFormat('dd MMM yyyy, hh:mm a').format(date.toLocal()),
                    style: TextStyle(fontSize: 11, color: Colors.grey.shade400)),
                  const SizedBox(height: 30),
                ],
              ),
            )
          ],
        );
      },
    );
  }

  String _getStatusLabel(String status) {
    switch(status) {
      case 'received_miami': return "Recibido en Miami";
      case 'in_transit': return "En tránsito a Panamá";
      case 'arrived': return "Recibido en Panamá";
      case 'ready_for_pickup': return "Listo para retirar";
      case 'delivered': return "Entregado";
      default: return status.replaceAll('_', ' ').toUpperCase();
    }
  }
}
