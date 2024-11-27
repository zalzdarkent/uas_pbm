import 'package:flutter/material.dart';

class PembelianScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Pembelian"),
        backgroundColor: Colors.blue[200],
      ),
      body: Center(
        child: const Text('Halaman Pembelian'),
      ),
    );
  }
}
