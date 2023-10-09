<?php

// Koneksi ke database
$dbhost = "localhost";
$dbuser = "id20844371_dapurbun";
$dbpass = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

// Periksa koneksi
if (mysqli_connect_errno()) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}

// Menerima data id_user dari metode POST
$id_user = $_POST['id_user'];

// Query SQL untuk mendapatkan data pemesanan dan rincian pesanan
$query = "SELECT p.*, rp.*
          FROM pemesanan p
          INNER JOIN rincian_pesanan rp ON FIND_IN_SET(rp.id_rincian_pesanan, p.id_rincian_pesanan) > 0
          WHERE p.id_user = '$id_user'";

$result = mysqli_query($conn, $query);

// Membuat array untuk menampung data
$data = array();

while ($row = mysqli_fetch_assoc($result)) {
    $data[] = $row;
}

// Mengirimkan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($data);

// Menutup koneksi
mysqli_close($conn);
?>
