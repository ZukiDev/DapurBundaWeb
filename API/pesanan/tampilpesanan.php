<?php

// Konfigurasi database
$dbhost = "localhost";
$dbuser = "id20844371_dapurbun";
$dbpass = "Sateayam1@";
$dbname = "id20844371_dapurbun";

// Membuat koneksi ke database
$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Memeriksa koneksi database
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Mendapatkan data id_user dari permintaan GET
$id_user = $_GET['id_user'];

// Membuat query untuk mengambil data pemesanan berdasarkan id_user
$query = "SELECT * FROM pemesanan WHERE id_user = $id_user";

// Menjalankan query
$result = $conn->query($query);

// Mengecek apakah query berhasil dijalankan
if ($result) {
    // Membuat array kosong untuk menampung data pemesanan
    $pemesanan = array();

    // Mendapatkan data pemesanan dan menambahkannya ke dalam array
    while ($row = $result->fetch_assoc()) {
        $pemesanan[] = $row;
    }

    // Mengecek apakah ada data pemesanan yang ditemukan
    if (count($pemesanan) > 0) {
        // Menyiapkan respon JSON
        $response = array(
            'status' => 'sukses',
            'message' => 'Data pemesanan ditemukan',
            'data' => $pemesanan
        );
    } else {
        // Menyiapkan respon JSON jika tidak ada data pemesanan ditemukan
        $response = array(
            'status' => 'gagal',
            'message' => 'Tidak ada data pemesanan'
        );
    }
} else {
    // Menyiapkan respon JSON jika query gagal dijalankan
    $response = array(
        'status' => 'gagal',
        'message' => 'Terjadi kesalahan saat mengambil data pemesanan'
    );
}

// Mengubah respon menjadi format JSON
$json_response = json_encode($response);

// Mengirim respon JSON ke klien
header('Content-Type: application/json');
echo $json_response;

// Menutup koneksi database
$conn->close();

?>
