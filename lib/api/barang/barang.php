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
        // Ambil dan validasi input
        $namabr = isset($input['namabr']) ? trim($input['namabr']) : '';
        $jenis = isset($input['jenis']) ? trim($input['jenis']) : '';
        $satuan = isset($input['satuan']) ? trim($input['satuan']) : '';
        $supplier_id = isset($input['supplier_id']) ? (int)$input['supplier_id'] : 0;

        // Periksa apakah semua data wajib diisi
        if (empty($namabr) || empty($jenis) || empty($satuan) || $supplier_id <= 0) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Semua data harus diisi dan supplier_id harus valid!'
            ]);
            exit;
        }

        // Periksa apakah supplier_id ada di tabel supplier
        $check_supplier = "SELECT sup_id FROM supplier WHERE sup_id = ?";
        $stmt = mysqli_prepare($koneksi, $check_supplier);
        mysqli_stmt_bind_param($stmt, 'i', $supplier_id);
        mysqli_stmt_execute($stmt);
        $supplier_result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($supplier_result) > 0) {
            // Query untuk memasukkan data ke tabel barang
            $query = "INSERT INTO barang (namabr, jenis, satuan, supplier_id) VALUES (?, ?, ?, ?)";
            $stmt_insert = mysqli_prepare($koneksi, $query);
            mysqli_stmt_bind_param($stmt_insert, 'sssi', $namabr, $jenis, $satuan, $supplier_id);
            $result = mysqli_stmt_execute($stmt_insert);

            // Periksa apakah data berhasil disimpan
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Data barang berhasil disimpan!'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Gagal menyimpan data barang: ' . mysqli_error($koneksi)
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Supplier dengan ID tersebut tidak ditemukan!'
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
?>
