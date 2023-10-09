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
        
        // Hitung bobot berdasarkan kriteria tertentu (0.4 untuk harga dan 0.6 untuk total terjual)
        $bobot = ($harga_produk * -0.3) + ($total_terjual * 0.7);

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

    //tampil
    if (count($recommendedProducts) > 0) {
        // Mulai menulis output sebagai HTML
        $count = 0; // Hitungan untuk membatasi produk dalam satu slide
        $slideCount = 0; // Hitungan untuk slide
        echo '<div class="carousel-item active" data-bs-interval="10000">';
        echo '<div class="row gx-3 h-100 align-items-center">';

        foreach ($recommendedProducts as $row) {
            if ($count === 5) {
                // Jika sudah mencapai 3 produk, buat slide baru
                $slideCount++;
                echo '</div></div>';
                echo '<div class="carousel-item" data-bs-interval="10000">';
                echo '<div class="row gx-3 h-100 align-items-center">';
                $count = 0;
            }

            echo '<div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
            echo '<div class="card card-span h-100 rounded-3"><img class="img-fluid rounded-3 h-100" src="image/web/' . $row['foto_produk'] . '" alt="'.$row['nama_produk'] .'" />';
            echo '<div class="card-body ps-0">';
            echo '<h5 class="fw-bold text-1000 text-truncate mb-1">' . $row['nama_produk'] . '</h5>';
            echo '<div><span class="text-warning me-2 bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
  <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
</svg></span><span class="text-primary">' . $row['total_terjual'] . ' Terjual</span></div>';
            echo '<span class="text-1000 fw-bold">Rp ' . number_format($row['harga_produk'], 2) . '</span>';
            echo '</div>';
            echo '</div>';
            echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
            echo '</div>';
            $count++;
        }

        // Jika jumlah produk kurang dari 5, isi slide terakhir dengan produk dari awal
        if ($count < 5) {
            $remaining = 5 - $count;
            foreach (array_slice($recommendedProducts, 0, $remaining) as $row) {
                echo '<div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
                echo '<div class="card card-span h-100 rounded-3"><img class="img-fluid rounded-3 h-100" src="image/web/' . $row['foto_produk'] . '" alt="'.$row['nama_produk'] .'" />';
                echo '<div class="card-body ps-0">';
                echo '<h5 class="fw-bold text-1000 text-truncate mb-1">' . $row['nama_produk'] . '</h5>';
                echo '<div><span class="text-warning me-2 bold"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bag" viewBox="0 0 16 16">
  <path d="M8 1a2.5 2.5 0 0 1 2.5 2.5V4h-5v-.5A2.5 2.5 0 0 1 8 1zm3.5 3v-.5a3.5 3.5 0 1 0-7 0V4H1v10a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V4h-3.5zM2 5h12v9a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V5z"/>
</svg></span><span class="text-primary">' . $row['total_terjual'] . ' Terjual</span></div>';
                echo '<span class="text-1000 fw-bold">Rp ' . number_format($row['harga_produk'], 2) . '</span>';
                echo '</div>';
                echo '</div>';
                echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
                echo '</div>';
            }
        }

        // Selesai menulis output sebagai HTML
        echo '</div></div>';
    } else {
        echo "Tidak ada data produk yang direkomendasikan.";
    }
} else {
    echo "Terjadi kesalahan saat mengambil data produk.";
}

// Tutup koneksi database
$conn->close();
?>
