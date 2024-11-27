import 'dart:convert';
import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;

class BarangScreen extends StatefulWidget {
  @override
  _BarangScreenState createState() => _BarangScreenState();
}

class _BarangScreenState extends State<BarangScreen> {
  final _formKey = GlobalKey<FormState>();
  final TextEditingController _namabrController = TextEditingController();
  final TextEditingController _jenisController = TextEditingController();
  final TextEditingController _satuanController = TextEditingController();
  String? _selectedSupplier;
  List<dynamic> _supplierList = [];
  List<dynamic> _barangList = [];

  // Fungsi untuk mengambil data barang dari API
  Future<void> fetchBarangData() async {
    final response = await http.get(Uri.parse('http://localhost/api/barang/get.php'));

    if (response.statusCode == 200) {
      setState(() {
        _barangList = json.decode(response.body)['data'];
      });
    } else {
      throw Exception('Failed to load barang data');
    }
  }

  // Fungsi untuk mengambil daftar supplier dari API
  Future<void> fetchSupplierData() async {
    final response = await http.get(Uri.parse('http://localhost/api/supplier/get.php'));

    if (response.statusCode == 200) {
      setState(() {
        _supplierList = json.decode(response.body)['data'];
      });
    } else {
      throw Exception('Failed to load supplier data');
    }
  }

  // Fungsi untuk menambahkan barang
  Future<void> addBarang() async {
    final response = await http.post(
      Uri.parse('http://localhost/api/barang/barang.php'),
      headers: <String, String>{
        'Content-Type': 'application/json',
      },
      body: json.encode({
        'namabr': _namabrController.text,
        'jenis': _jenisController.text,
        'satuan': _satuanController.text,
        'supplier_id': int.parse(_selectedSupplier!),
      }),
    );

    if (response.statusCode == 200) {
      fetchBarangData(); // Update data setelah barang ditambahkan
      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text('Barang berhasil ditambahkan!')),
      );
    } else {
      throw Exception('Failed to add barang');
    }
  }

  @override
  void initState() {
    super.initState();
    fetchBarangData(); // Ambil data barang saat pertama kali membuka halaman
    fetchSupplierData(); // Ambil daftar supplier saat pertama kali membuka halaman
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Barang"),
        backgroundColor: Colors.blue[200],
      ),
      body: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          children: [
            // Form input untuk data barang
            Form(
              key: _formKey,
              child: Column(
                children: [
                  TextFormField(
                    controller: _namabrController,
                    decoration: InputDecoration(labelText: 'Nama Barang'),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Nama barang tidak boleh kosong';
                      }
                      return null;
                    },
                  ),
                  TextFormField(
                    controller: _jenisController,
                    decoration: InputDecoration(labelText: 'Jenis Barang'),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Jenis barang tidak boleh kosong';
                      }
                      return null;
                    },
                  ),
                  TextFormField(
                    controller: _satuanController,
                    decoration: InputDecoration(labelText: 'Satuan Barang'),
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Satuan barang tidak boleh kosong';
                      }
                      return null;
                    },
                  ),
                  // Dropdown untuk memilih supplier
                  DropdownButtonFormField<String>(
                    value: _selectedSupplier,
                    hint: Text('Pilih Supplier'),
                    items: _supplierList.map((supplier) {
                      return DropdownMenuItem<String>(
                        value: supplier['sup_id'].toString(),
                        child: Text(supplier['nama_supplier']),
                      );
                    }).toList(),
                    onChanged: (String? newValue) {
                      setState(() {
                        _selectedSupplier = newValue;
                      });
                    },
                    validator: (value) {
                      if (value == null || value.isEmpty) {
                        return 'Supplier harus dipilih';
                      }
                      return null;
                    },
                  ),
                  SizedBox(height: 20),
                  ElevatedButton(
                    onPressed: () {
                      if (_formKey.currentState!.validate()) {
                        addBarang(); // Menambahkan barang jika validasi berhasil
                      }
                    },
                    child: Text('Tambah Barang'),
                  ),
                ],
              ),
            ),
            SizedBox(height: 20),
            // Tabel data barang
            Expanded(
              child: ListView.builder(
                itemCount: _barangList.length,
                itemBuilder: (context, index) {
                  return Card(
                    margin: EdgeInsets.symmetric(vertical: 8),
                    child: ListTile(
                      title: Text(_barangList[index]['namabr']),
                      subtitle: Text('${_barangList[index]['jenis']} - ${_barangList[index]['satuan']}'),
                      trailing: Text(_barangList[index]['nama_supplier']),
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
