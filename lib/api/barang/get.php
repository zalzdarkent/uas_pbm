<?php
include('../config/config.php');

// Set header untuk respons API
header('Content-Type: application/json');

// Metode HTTP harus GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mengambil data barang beserta nama supplier
    $query = "SELECT b.namabr, b.jenis, b.satuan, s.nama_supplier
              FROM barang b
              JOIN supplier s ON b.supplier_id = s.sup_id";
    
    $result = mysqli_query($koneksi, $query);

    // Periksa apakah query berhasil
    if ($result) {
        $barang_list = [];

        // Ambil data barang dan supplier
        while ($row = mysqli_fetch_assoc($result)) {
            $barang_list[] = [
                'namabr' => $row['namabr'],
                'jenis' => $row['jenis'],
                'satuan' => $row['satuan'],
                'nama_supplier' => $row['nama_supplier']
            ];
        }

        // Tampilkan hasil dalam format JSON
        echo json_encode([
            'status' => 'success',
            'data' => $barang_list
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Gagal mengambil data barang: ' . mysqli_error($koneksi)
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan!'
    ]);
}

// Tutup koneksi
mysqli_close($koneksi);
?>
