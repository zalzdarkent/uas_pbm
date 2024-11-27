<?php
include('../config/config.php');

// Set header untuk respons API
header('Content-Type: application/json');

// Metode HTTP harus POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Validasi input JSON
    if (is_array($input)) {
        $supplier_id = isset($input['supplier_id']) ? (int)$input['supplier_id'] : 0;
        $barang_id = isset($input['barang_id']) ? (int)$input['barang_id'] : 0;
        $qty = isset($input['qty']) ? (int)$input['qty'] : 0;

        // Periksa apakah semua data wajib diisi
        if ($supplier_id <= 0 || $barang_id <= 0 || $qty <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Semua data (supplier_id, barang_id, qty) harus diisi dan valid!'
            ]);
            exit;
        }

        // Periksa apakah supplier_id ada di tabel supplier
        $check_supplier = "SELECT sup_id FROM supplier WHERE sup_id = $supplier_id";
        $supplier_result = mysqli_query($koneksi, $check_supplier);

        if (mysqli_num_rows($supplier_result) === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Supplier dengan ID tersebut tidak ditemukan!'
            ]);
            exit;
        }

        // Periksa apakah barang_id ada di tabel barang
        $check_barang = "SELECT br_id FROM barang WHERE br_id = $barang_id";
        $barang_result = mysqli_query($koneksi, $check_barang);

        if (mysqli_num_rows($barang_result) === 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Barang dengan ID tersebut tidak ditemukan!'
            ]);
            exit;
        }

        // Query untuk memasukkan data pembelian
        $query = "INSERT INTO pembelian (supplier_id, barang_id, qty) 
                  VALUES ($supplier_id, $barang_id, $qty)";
        $result = mysqli_query($koneksi, $query);

        // Periksa apakah data berhasil disimpan
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data pembelian berhasil disimpan!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data pembelian: ' . mysqli_error($koneksi)
            ]);
        }
    } else {
        echo json_encode([
            'status' => 'error',
            'message' => 'Format data tidak valid!'
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
