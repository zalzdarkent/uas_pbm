<?php
include('../config/config.php');

// Set header untuk respons API
header('Content-Type: application/json');

// Metode HTTP harus GET
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query untuk mengambil data supplier
    $query = "SELECT sup_id, nama_supplier, no_telp, perusahaan FROM supplier";
    $result = mysqli_query($koneksi, $query);

    // Cek apakah data ditemukan
    if (mysqli_num_rows($result) > 0) {
        $suppliers = [];

        // Ambil data supplier dan simpan dalam array
        while ($row = mysqli_fetch_assoc($result)) {
            $suppliers[] = $row;
        }

        // Kembalikan data dalam format JSON
        echo json_encode([
            'status' => 'success',
            'data' => $suppliers
        ]);
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Data supplier tidak ditemukan!'
        ]);
    }
} else {
    echo json_encode([
        'status' => 'error',
        'message' => 'Metode tidak diizinkan! Harus GET'
    ]);
}

// Tutup koneksi
mysqli_close($koneksi);
?>
