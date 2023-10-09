<?php
$host = "localhost";
$usernamedb = "id20844371_dapurbun";
$passworddb = "Sateayam1@";
$dbname = "id20844371_dapurbun";

// Menerima data yang dikirimkan dari aplikasi Flutter
$namaLengkap = $_POST['nama_lengkap'];
$jenisKelamin = $_POST['jenis_kelamin'];
$tanggalLahir = $_POST['tanggal_lahir'];
$email = $_POST['email'];
$nomerTelepon = $_POST['nomer_telepon'];
$password = $_POST['password'];

// Membuat koneksi ke database
$conn = mysqli_connect($host, $usernamedb, $passworddb, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Membuat query untuk memasukkan data ke tabel `user`
$sql = "INSERT INTO user (nama_lengkap, jenis_kelamin, tanggal_lahir, email, nomer_telepon, password)
VALUES ('$namaLengkap', '$jenisKelamin', '$tanggalLahir', '$email', '$nomerTelepon', '$password')";

if ($conn->query($sql) === TRUE) {
    $response = array(
        "status" => "success",
        "message" => "Registrasi berhasil"
    );
} else {
    $response = array(
        "status" => "error",
        "message" => "Registrasi gagal: " . $conn->error
    );
}

// Menutup koneksi ke database
$conn->close();

// Mengembalikan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

?>