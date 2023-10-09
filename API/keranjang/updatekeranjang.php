<?php
// Mendapatkan data yang dikirimkan melalui metode POST dari Flutter
$idkeranjang = $_POST['idkeranjang'];
$jml = $_POST['jml'];

// Koneksi ke database
$host = "localhost";
$username = "id20844371_dapurbun";
$password = "Sateayam1@";
$database = "id20844371_dapurbun";

$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Membuat query untuk memperbarui jumlah produk dalam keranjang
$query = "UPDATE keranjang SET jumlah_produk = '$jml' WHERE id_keranjang = '$idkeranjang'";

// Mengeksekusi query
if (mysqli_query($connection, $query)) {
    // Jika pembaruan berhasil, mengirimkan respon sukses ke Flutter
    $response = array(
        'success' => true,
        'message' => 'Jumlah produk dalam keranjang berhasil diperbarui'
    );
} else {
    // Jika terjadi kesalahan saat memperbarui, mengirimkan respon error ke Flutter
    $response = array(
        'success' => false,
        'message' => 'Terjadi kesalahan saat memperbarui jumlah produk dalam keranjang: ' . mysqli_error($connection)
    );
}

// Menutup koneksi database
mysqli_close($connection);

// Mengirimkan respon dalam format JSON ke Flutter
header('Content-Type: application/json');
echo json_encode($response);
?>
