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

// Query untuk mengambil data produk
$sql = "SELECT * FROM produk";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // Mulai menulis output sebagai HTML
    $count = 0; // Hitungan untuk membatasi produk dalam satu slide
    $slideCount = 0; // Hitungan untuk slide
    echo '<div class="carousel-item active" data-bs-interval="10000">';
    echo '<div class="row gx-3 h-100 align-items-center">';

    while ($row = $result->fetch_assoc()) {
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
        echo '<div><span class="text-warning me-2"><i class="fas fa-map-marker-alt"></i></span><span class="text-primary">' . $row['nama_produk'] . '</span></div>';
        echo '<span class="text-1000 fw-bold">$' . number_format($row['harga_produk'], 2) . '</span>';
        echo '</div>';
        echo '</div>';
        echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
        echo '</div>';
        $count++;
    }

    // Jika jumlah produk kurang dari 3, isi slide terakhir dengan produk dari awal
    if ($count < 5) {
        $remaining = 5 - $count;
        $result->data_seek(0); // Kembali ke produk pertama
        while ($remaining > 0) {
            $row = $result->fetch_assoc();
            echo '<div class="col-sm-6 col-md-4 col-xl mb-5 h-100">';
            echo '<div class="card card-span h-100 rounded-3"><img class="img-fluid rounded-3 h-100" src="image/web/' . $row['foto_produk'] . '" alt="'.$row['nama_produk'] .'" />';
            echo '<div class="card-body ps-0">';
            echo '<h5 class="fw-bold text-1000 text-truncate mb-1">' . $row['nama_produk'] . '</h5>';
            echo '<div><span class="text-warning me-2"><i class="fas fa-map-marker-alt"></i></span><span class="text-primary">' . $row['nama_produk'] . '</span></div>';
            echo '<span class="text-1000 fw-bold">$' . number_format($row['harga_produk'], 2) . '</span>';
            echo '</div>';
            echo '</div>';
            echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
            echo '</div>';
            $remaining--;
        }
    }
    
    // Selesai menulis output sebagai HTML
    echo '</div></div>';
} else {
    echo "Tidak ada data produk.";
}

// Tutup koneksi database
$conn->close();
?>