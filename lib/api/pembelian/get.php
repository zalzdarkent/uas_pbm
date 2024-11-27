<?php
include('../config/config.php');

// Set header untuk respons API
header('Content-Type: application/json');

// Metode HTTP harus GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mengambil data dengan join
    $query = "
        SELECT 
            barang.namabr AS namabr, 
            supplier.nama_supplier AS nama_supplier, 
            pembelian.qty AS qty
        FROM 
            pembelian
        INNER JOIN 
            barang ON pembelian.barang_id = barang.br_id
        INNER JOIN 
            supplier ON pembelian.supplier_id = supplier.sup_id
    ";

    $result = mysqli_query($koneksi, $query);

    // Periksa apakah ada data yang ditemukan
    if ($result && mysqli_num_rows($result) > 0) {
        $data = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $data[] = [
                'namabr' => $row['namabr'],
                'nama_supplier' => $row['nama_supplier'],
                'qty' => $row['qty']
            ];
        }

        // Respons data berhasil ditemukan
        echo json_encode([
            'status' => 'success',
            'data' => $data
        ]);
    } else {
        // Respons jika tidak ada data
        echo json_encode([
            'status' => 'error',
            'message' => 'Tidak ada data pembelian yang ditemukan!'
        ]);
    }
} else {
    // Respons jika metode tidak sesuai
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan!'
    ]);
}

// Tutup koneksi
mysqli_close($koneksi);

?>