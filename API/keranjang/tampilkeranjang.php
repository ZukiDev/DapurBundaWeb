<?php

// Koneksi ke database
$dbhost = "localhost";
$dbuser = "id20844371_dapurbun";
$dbpass = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

// Terima permintaan GET dari Flutter
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['id_user'])) {
        $id_user = $_GET['id_user'];

        // Query data keranjang dengan tanggal yang sama dan id_user tertentu
        $query = "SELECT k.tanggal_booking,k.waktu_booking, GROUP_CONCAT(k.id_keranjang SEPARATOR ', ') AS idkeranjang, GROUP_CONCAT(k.id_kategori_pesanan SEPARATOR ', ') AS idkatepes, GROUP_CONCAT(k.id_produk SEPARATOR ', ') AS idproduk, GROUP_CONCAT(p.nama_produk SEPARATOR ', ') AS produk, GROUP_CONCAT(p.deskripsi_produk SEPARATOR ', ') AS desproduk, GROUP_CONCAT(`catatan` SEPARATOR ', ') AS catatan, GROUP_CONCAT(k.jumlah_produk SEPARATOR ', ') AS jml, GROUP_CONCAT(p.foto_produk SEPARATOR ', ') AS foto, GROUP_CONCAT(p.harga_produk SEPARATOR ', ') AS harga, SUM(k.jumlah_produk) AS jumlah_item, SUM(p.harga_produk * k.jumlah_produk) AS total_harga FROM keranjang k INNER JOIN produk p ON k.id_produk = p.id_produk WHERE k.id_user = $id_user GROUP BY k.tanggal_booking,k.waktu_booking";
        $result = $conn->query($query);

        if ($result === false) {
            die("Error executing query: " . $conn->error);
        }

        $response = array();
        while ($row = $result->fetch_assoc()) {
            $row['idkeranjang'] = explode(', ', $row['idkeranjang']);
            $row['idkatepes'] = explode(', ', $row['idkatepes']);
            $row['idproduk'] = explode(', ', $row['idproduk']);
            $row['produk'] = explode(', ', $row['produk']);
            $row['desproduk'] = explode(', ', $row['desproduk']);
            $row['catatan'] = explode(', ', $row['catatan']);
            $row['jml'] = explode(', ', $row['jml']);
            $row['foto'] = explode(', ', $row['foto']);
            $row['harga'] = explode(', ', $row['harga']);
            $row['jumlah_item'] = explode(', ', $row['jumlah_item']);
            $response[] = $row;
        }

        // Kembalikan data sebagai respons API dalam format JSON
        header('Content-Type: application/json');
        echo json_encode($response);
    } else {
        echo "ID User tidak ditemukan.";
    }
}

$conn->close();
?>
