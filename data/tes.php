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
    echo '<div class="row gx-2">';
    
    while ($row = $result->fetch_assoc()) {
        echo '<div class="col-sm-6 col-md-4 col-lg-3 h-100 mb-5">';
            echo '<div class="card card-span h-100 rounded-3"><img class="img-fluid rounded-3 h-100" src="image/web/' . $row['foto_produk'] . '" alt="'.$row['nama_produk'] .'" />';
            echo '<div class="card-body ps-0">';
            echo '<h5 class="fw-bold text-1000 text-truncate mb-1">' . $row['nama_produk'] . '</h5>';
            echo '<span class="text-1000 fw-bold">Rp ' . number_format($row['harga_produk'], 2) . '</span>';
            echo '<p class="flex-1 md-0">' . $row['deskripsi_produk'] . '</p>';
            echo '</div>';
            echo '</div>';
            echo '<div class="d-grid gap-2"><a class="btn btn-lg btn-danger" href="#!" role="button">Order now</a></div>';
            echo '</div>';
    }
    echo '</div>';
} else {
    echo "Tidak ada data produk.";
}

// Tutup koneksi database
$conn->close();
?>