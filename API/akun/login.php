<?php

// Menghubungkan ke database
$host = "localhost";
$usernamedb = "id20844371_dapurbun";
$passworddb = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = mysqli_connect($host, $usernamedb, $passworddb, $dbname);

// Cek koneksi
if (!$conn) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

// Mendefinisikan endpoint untuk login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email']) && isset($_POST['password'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Melakukan query untuk mencari pengguna berdasarkan email dan password
    $sql = "SELECT * FROM user WHERE email = '$email' AND password = '$password'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        // Login berhasil
        $row = mysqli_fetch_assoc($result);
        $response = array(
            'status' => 'success',
            'message' => 'Login berhasil',
            'data' => $row
        );
    } else {
        // Login gagal
        $response = array(
            'status' => 'error',
            'message' => 'Login gagal. Email atau password salah.'
        );
    }

    // Mengirimkan response dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($response);
    exit();
}

// Mendefinisikan endpoint untuk menampilkan semua data pengguna
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['get_users'])) {
    // Melakukan query untuk mendapatkan semua data pengguna
    $sql = "SELECT * FROM user";
    $result = mysqli_query($conn, $sql);

    $users = array();
    if (mysqli_num_rows($result) > 0) {
        // Menambahkan data pengguna ke dalam array
        while ($row = mysqli_fetch_assoc($result)) {
            $users[] = $row;
        }
    }

    // Mengirimkan response dalam format JSON
    header('Content-Type: application/json');
    echo json_encode($users);
    exit();
}

// Menutup koneksi database
mysqli_close($conn);
?>
