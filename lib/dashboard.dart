import 'package:flutter/material.dart';
import 'barang.dart'; // Import halaman barang
import 'supplier.dart'; // Import halaman supplier
import 'pembelian.dart'; // Import halaman pembelian

class DashboardScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text("Dashboard"),
        backgroundColor: Colors.blue[200],
      ),
      body: Center(
        child: Padding(
          padding: const EdgeInsets.all(20.0),
          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [
              ElevatedButton(
                onPressed: () {
                  // Arahkan ke halaman Barang
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => BarangScreen()),
                  );
                },
                child: const Text('Barang'),
              ),
              const SizedBox(height: 20),
              ElevatedButton(
                onPressed: () {
                  // Arahkan ke halaman Supplier
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => SupplierScreen()),
                  );
                },
                child: const Text('Supplier'),
              ),
              const SizedBox(height: 20),
              ElevatedButton(
                onPressed: () {
                  // Arahkan ke halaman Pembelian
                  Navigator.push(
                    context,
                    MaterialPageRoute(builder: (context) => PembelianScreen()),
                  );
                },
                child: const Text('Pembelian'),
              ),
            ],
          ),
        ),
      ),
    );
  }
}
