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
        $nama_supplier = isset($input['nama_supplier']) ? mysqli_real_escape_string($koneksi, $input['nama_supplier']) : '';
        $no_telp = isset($input['no_telp']) ? mysqli_real_escape_string($koneksi, $input['no_telp']) : '';
        $perusahaan = isset($input['perusahaan']) ? mysqli_real_escape_string($koneksi, $input['perusahaan']) : '';

        // Periksa apakah semua data wajib diisi
        if (empty($nama_supplier) || empty($no_telp) || empty($perusahaan)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Semua data harus diisi!'
            ]);
            exit;
        }

        // Query untuk memasukkan data ke tabel supplier
        $query = "INSERT INTO supplier (nama_supplier, no_telp, perusahaan) 
                  VALUES ('$nama_supplier', '$no_telp', '$perusahaan')";
        $result = mysqli_query($koneksi, $query);

        // Periksa apakah data berhasil disimpan
        if ($result) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Data supplier berhasil disimpan!'
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Gagal menyimpan data supplier: ' . mysqli_error($koneksi)
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
