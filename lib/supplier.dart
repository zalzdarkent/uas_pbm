import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class SupplierScreen extends StatefulWidget {
  @override
  _SupplierScreenState createState() => _SupplierScreenState();
}

class _SupplierScreenState extends State<SupplierScreen> {
  // List untuk menyimpan data supplier
  List<dynamic> suppliers = [];

  // Kontroller untuk form input
  final TextEditingController _namaSupplierController = TextEditingController();
  final TextEditingController _noTelpController = TextEditingController();
  final TextEditingController _perusahaanController = TextEditingController();

  @override
  void initState() {
    super.initState();
    // Memanggil fungsi untuk mendapatkan data supplier saat layar dimuat
    fetchSuppliers();
  }

  // Fungsi untuk mengambil data supplier
  Future<void> fetchSuppliers() async {
    final response =
        await http.get(Uri.parse('http://localhost/api/supplier/get.php'));

    if (response.statusCode == 200) {
      var data = jsonDecode(response.body);
      if (data['status'] == 'success' && data['data'] != null) {
        setState(() {
          suppliers = data['data'];
        });
      }
    } else {
      throw Exception('Failed to load suppliers');
    }
  }

  // Fungsi untuk menambahkan supplier
  Future<void> addSupplier() async {
    final response = await http.post(
      Uri.parse('http://localhost/api/supplier/supplier.php'),
      headers: {"Content-Type": "application/json"},
      body: jsonEncode({
        'nama_supplier': _namaSupplierController.text,
        'no_telp': _noTelpController.text,
        'perusahaan': _perusahaanController.text,
      }),
    );

    if (response.statusCode == 200) {
      var data = jsonDecode(response.body);
      if (data['status'] == 'success') {
        // Jika berhasil menambahkan, reload data supplier
        fetchSuppliers();
        // Reset form
        _namaSupplierController.clear();
        _noTelpController.clear();
        _perusahaanController.clear();
      }
    } else {
      throw Exception('Failed to add supplier');
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Supplier"),
        backgroundColor: Colors.blue[200],
      ),
      body: Padding(
        padding: const EdgeInsets.all(8.0),
        child: Column(
          children: [
            // Form Input Supplier
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8.0),
              child: TextField(
                controller: _namaSupplierController,
                decoration: const InputDecoration(labelText: 'Nama Supplier'),
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8.0),
              child: TextField(
                controller: _noTelpController,
                decoration: const InputDecoration(labelText: 'No. Telp'),
              ),
            ),
            Padding(
              padding: const EdgeInsets.symmetric(vertical: 8.0),
              child: TextField(
                controller: _perusahaanController,
                decoration: const InputDecoration(labelText: 'Perusahaan'),
              ),
            ),
            ElevatedButton(
              onPressed: addSupplier,
              child: const Text('Tambah Supplier'),
            ),

            const SizedBox(height: 16),

            // Tabel Supplier
            Expanded(
              child: ListView.builder(
                itemCount: suppliers.length,
                itemBuilder: (context, index) {
                  return Card(
                    margin: const EdgeInsets.symmetric(vertical: 4),
                    child: ListTile(
                      title: Text(suppliers[index]['nama_supplier']),
                      subtitle: Text('Telp: ${suppliers[index]['no_telp']}'),
                      trailing: Text(suppliers[index]['perusahaan']),
                    ),
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
