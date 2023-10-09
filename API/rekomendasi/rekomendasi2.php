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

// Mengambil data produk dan mengelompokkannya berdasarkan ID produk
$query = "SELECT id_produk, SUM(harga_produk) AS total_harga, SUM(jumlah_produk) AS total_jumlah FROM rincian_pesanan GROUP BY id_produk";
$result = $conn->query($query);

if ($result) {
    $recommendations = array();

    // Menghitung nilai "weight product" untuk setiap produk dan menyimpannya dalam array
    while ($row = $result->fetch_assoc()) {
        $id_produk = $row['id_produk'];
        $total_harga = $row['total_harga'];
        $total_jumlah = $row['total_jumlah'];

        $weight_product = ($total_harga * -0.3) + ($total_jumlah * 0.7);

        $recommendations[] = array(
            'id_produk' => $id_produk,
            'weight_product' => $weight_product
        );
    }

    // Mengurutkan produk berdasarkan "weight product"
    usort($recommendations, function ($a, $b) {
        return $b['weight_product'] - $a['weight_product'];
    });

    // Menyiapkan respon JSON
    $response = array(
        'status' => 'sukses',
        'message' => 'Rekomendasi produk berhasil dibuat',
        'recommendations' => $recommendations
    );
} else {
    // Menyiapkan respon JSON jika terjadi kesalahan saat mengambil data
    $response = array(
        'status' => 'gagal',
        'message' => 'Terjadi kesalahan saat mengambil data'
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