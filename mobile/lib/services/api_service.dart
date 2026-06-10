import 'dart:convert';
import 'package:http/http.dart' as http;
import 'package:flutter_secure_storage/flutter_secure_storage.dart';

class ApiService {
  final String baseUrl = "http://10.0.2.2/api/v1"; // Emulator default for localhost
  final storage = const Flutter_secure_storage();

  Future<Map<String, dynamic>?> login(String email, String password) async {
    try {
      final response = await http.post(
        Uri.parse("$baseUrl/login"),
        headers: {'Content-Type': 'application/json', 'Accept': 'application/json'},
        body: jsonEncode({
          'email': email,
          'password': password,
          'device_name': 'mobile_app',
        }),
      );

      if (response.statusCode == 200) {
        final data = jsonDecode(response.body);
        await storage.write(key: 'token', value: data['token']);
        return data;
      }
      return null;
    } catch (e) {
      return null;
    }
  }

  Future<String?> getToken() async {
    return await storage.read(key: 'token');
  }

  Future<void> logout() async {
    final token = await getToken();
    await http.post(
      Uri.parse("$baseUrl/logout"),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );
    await storage.delete(key: 'token');
  }

  Future<Map<String, dynamic>?> scanPackage(String tracking) async {
    final token = await getToken();
    final response = await http.post(
      Uri.parse("$baseUrl/warehouse/scan"),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({'tracking': tracking}),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }

  Future<List<dynamic>> getWarehouses() async {
    final token = await getToken();
    final response = await http.get(
      Uri.parse("$baseUrl/warehouse/list"),
      headers: {
        'Authorization': 'Bearer $token',
        'Accept': 'application/json',
      },
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return [];
  }

  Future<Map<String, dynamic>?> submitBulkReception(int warehouseId, List<Map<String, dynamic>> items) async {
    final token = await getToken();
    final response = await http.post(
      Uri.parse("$baseUrl/warehouse/bulk-receive"),
      headers: {
        'Authorization': 'Bearer $token',
        'Content-Type': 'application/json',
        'Accept': 'application/json',
      },
      body: jsonEncode({
        'warehouse_id': warehouseId,
        'items': items,
      }),
    );

    if (response.statusCode == 200) {
      return jsonDecode(response.body);
    }
    return null;
  }
}

class Flutter_secure_storage extends FlutterSecureStorage {
  const Flutter_secure_storage() : super();
}
