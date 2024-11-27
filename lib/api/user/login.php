<?php
include('../config/config.php');

// Set header untuk respons API
header('Content-Type: application/json');

// Metode HTTP harus POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ambil data JSON dari request body
    $input = json_decode(file_get_contents('php://input'), true);

    // Periksa apakah input JSON valid
    if (is_array($input)) {
        $email = isset($input['email']) ? mysqli_real_escape_string($koneksi, $input['email']) : '';
        $password = isset($input['password']) ? $input['password'] : '';

        // Periksa apakah input kosong
        if (empty($email) || empty($password)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email dan password harus diisi!'
            ]);
            exit;
        }

        // Query untuk mencari pengguna berdasarkan email
        $query = "SELECT * FROM user WHERE email = '$email'";
        $result = mysqli_query($koneksi, $query);

        // Periksa apakah pengguna ditemukan
        if (mysqli_num_rows($result) > 0) {
            $user = mysqli_fetch_assoc($result);
            $hashedPassword = $user['password']; // Asumsikan kolom password menyimpan hash

            // Verifikasi password
            if (password_verify($password, $hashedPassword)) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Login berhasil!',
                    'data' => [
                        'email' => $user['email'],
                        'username' => $user['username'] // Contoh kolom lain
                    ]
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Password salah!'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Email tidak ditemukan!'
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