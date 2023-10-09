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

    // Mulai menulis output sebagai HTML
    $count = 0; // Hitungan untuk membatasi produk dalam satu slide
    $slideCount = 0; // Hitungan untuk slide
    echo '<div class="carousel-item active" data-bs-interval="10000">';
    echo '<div class="row gx-3 h-100 align-items-center">';

    foreach ($recommendedProducts as $product) {
        if ($count === 5) {
            // Jika sudah mencapai 5 produk, buat slide baru
            $slideCount++;
            echo '</div></div>';
            echo '<div class="carousel-item" data-bs-interval="10000">';
            echo '<div class="row gx-3 h-100 align-items-center">';
            $count = 0;
        }

        echo '<div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
        echo '<div class="card card-span h-100 rounded-3"><img class="img-fluid rounded-3 h-100" src="image/web/' . $product['foto_produk'] . '" alt="'.$product['nama_produk'] .'" />';
        echo '<div class="card-body ps-0">';
        echo '<h5 class="fw-bold text-1000 text-truncate mb-1">' . $product['nama_produk'] . '</h5>';
        echo '<div><span class="text-warning me-2"><i class="fas fa-map-marker-alt"></i></span><span class="text-primary">' . $product['nama_produk'] . '</span></div>';
        echo '<span class="text-1000 fw-bold">$' . number_format($product['harga_produk'], 2) . '</span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
        echo '</div>';
        $count++;
    }

    // Selesai menulis output sebagai HTML
    echo '</div></div>';
} else {
    echo "Tidak ada produk yang direkomendasikan.";
}

// Menutup koneksi database
$conn->close();
?>
