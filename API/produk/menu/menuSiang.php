<?php
// Koneksi ke database
$servername = "localhost";
$username = "id20844371_dapurbun";
$password = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Query untuk mengambil data dari tabel produk
$sql = "SELECT * FROM `produk` WHERE `id_kategori_menu` = 2;";
$result = $conn->query($sql);

// Membuat array kosong untuk menyimpan data produk
$produkArray = array();

// Mengambil setiap baris data dan memasukkannya ke array
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $produkArray[] = $row;
    }
}

// Menyusun respons dalam format JSON
$response = array(
    "message" => "Data produk berhasil ditemukan.",
    "data" => $produkArray
);

// Mengirimkan response dalam format JSON
header('Content-Type: application/json');
echo json_encode($response);

// Menutup koneksi database
$conn->close();
?>
