<?php
// Mendapatkan data yang dikirimkan melalui metode POST dari Flutter
$id_user = $_POST['id_user'];
$id_kategori_pesanan = $_POST['id_kategori_pesanan'];
$id_produk = $_POST['id_produk'];
$nama_produk = $_POST['nama_produk'];
$deskripsi_produk = $_POST['deskripsi_produk'];
$harga_produk = $_POST['harga_produk'];
$jumlah_produk = $_POST['jumlah_produk'];
$tanggal_booking = $_POST['tanggal_booking'];
$waktu_booking = $_POST['waktu_booking'];
$total_harga = $_POST['total_harga'];
$catatan = $_POST['catatan'];

// Koneksi ke database
$host = "localhost";
$username = "id20844371_dapurbun";
$password = "Sateayam1@";
$database = "id20844371_dapurbun";

$connection = mysqli_connect($host, $username, $password, $database);
if (!$connection) {
    die('Koneksi database gagal: ' . mysqli_connect_error());
}

// Membuat query untuk memasukkan data ke tabel keranjang
$query = "INSERT INTO keranjang (id_keranjang, id_user, id_kategori_pesanan, id_produk, nama_produk, deskripsi_produk, harga_produk, jumlah_produk, tanggal_booking, waktu_booking, total_harga, catatan) 
          VALUES (null, '$id_user', '$id_kategori_pesanan', '$id_produk', '$nama_produk', '$deskripsi_produk', '$harga_produk', '$jumlah_produk', '$tanggal_booking', '$waktu_booking', '$total_harga', '$catatan')";

// Mengeksekusi query
if (mysqli_query($connection, $query)) {
    // Jika data berhasil dimasukkan ke database, mengirimkan respon sukses ke Flutter
    $response = array(
        'status' => 'success',
        'message' => 'Data berhasil dimasukkan ke database'
    );
} else {
    // Jika terjadi kesalahan saat memasukkan data, mengirimkan respon error ke Flutter
    $response = array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memasukkan data ke database: ' . mysqli_error($connection)
    );
}

// Menutup koneksi database
mysqli_close($connection);

// Mengirimkan respon dalam format JSON ke Flutter
header('Content-Type: application/json');
echo json_encode($response);
?>
