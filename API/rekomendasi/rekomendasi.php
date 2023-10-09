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

// Query untuk mengambil data produk dengan total terjual dan harga produk
$query = "SELECT id_produk, harga_produk, SUM(jumlah_produk) AS total_terjual FROM rincian_pesanan GROUP BY id_produk";

$result = $conn->query($query);

if ($result) {
    // Inisialisasi array untuk menyimpan produk yang direkomendasikan
    $recommendedProducts = array();

    // Mendapatkan data produk dan menghitung bobot
    while ($row = $result->fetch_assoc()) {
        $id_produk = $row['id_produk'];
        $harga_produk = $row['harga_produk'];
        $total_terjual = $row['total_terjual'];
        
        // Hitung bobot berdasarkan kriteria tertentu (misalnya, harga rendah dan total terjual tinggi)
        $bobot = $harga_produk * $total_terjual;

        // Tambahkan produk ke dalam array rekomendasi
        $recommendedProducts[] = array(
            'id_produk' => $id_produk,
            'harga_produk' => $harga_produk,
            'total_terjual' => $total_terjual,
            'bobot' => $bobot
        );
    }

    // Urutkan produk berdasarkan bobot
    usort($recommendedProducts, function($a, $b) {
        return $b['bobot'] - $a['bobot'];
    });

    // Hapus duplikat produk dengan id_produk yang sama
    $uniqueProducts = array_unique(array_column($recommendedProducts, 'id_produk'));

    // Mengambil data produk yang direkomendasikan dari tabel produk
    $recommendedProductDetails = array();
    foreach ($uniqueProducts as $productId) {
        $queryProduct = "SELECT * FROM produk WHERE id_produk = $productId";
        $resultProduct = $conn->query($queryProduct);
        if ($resultProduct && $rowProduct = $resultProduct->fetch_assoc()) {
            $recommendedProductDetails[] = $rowProduct;
        }
    }

    // Menggabungkan data produk yang direkomendasikan dengan detailnya
    $recommendedProducts = array_intersect_key($recommendedProducts, $uniqueProducts);
    foreach ($recommendedProducts as &$product) {
        foreach ($recommendedProductDetails as $productDetail) {
            if ($product['id_produk'] == $productDetail['id_produk']) {
                $product['nama_produk'] = $productDetail['nama_produk'];
                $product['deskripsi_produk'] = $productDetail['deskripsi_produk'];
                $product['harga_produk'] = $productDetail['harga_produk'];
                $product['foto_produk'] = $productDetail['foto_produk'];
                break;
            }
        }
    }

    // Buat respon JSON berisi produk yang direkomendasikan beserta detailnya
    $response = array(
        'status' => 'sukses',
        'message' => 'Produk direkomendasikan',
        'data' => $recommendedProducts
    );
} else {
    $response = array(
        'status' => 'gagal',
        'message' => 'Terjadi kesalahan saat mengambil data produk'
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
