<?php
// Koneksi ke database
$dbhost = "localhost";
$dbuser = "id20844371_dapurbun";
$dbpass = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mendapatkan data id_rincian_pesanan dari request POST
$id_rincian_pesanan = $_POST['id_rincian_pesanan'] ?? '';

// Memeriksa apakah $id_rincian_pesanan tidak kosong
if (!empty($id_rincian_pesanan)) {
    // Mengubah id_rincian_pesanan menjadi array
    $id_rincian_pesanan_array = explode(",", $id_rincian_pesanan);

    // Mengubah array menjadi string yang dipisahkan oleh koma
    $id_rincian_pesanan_string = implode(",", $id_rincian_pesanan_array);

    // Membuat query SQL untuk mendapatkan data dari tabel rincian_pesanan
    $sql = "SELECT rp.*, p.nama_produk, p.deskripsi_produk, p.harga_produk, p.foto_produk
FROM rincian_pesanan rp
INNER JOIN produk p ON rp.id_produk = p.id_produk
WHERE rp.id_rincian_pesanan IN ($id_rincian_pesanan_string)";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Membuat array untuk menyimpan data
        $data = array();

        // Mendapatkan data setiap baris hasil query
        while ($row = $result->fetch_assoc()) {
            // Menambahkan baris data ke array
            $data[] = $row;
        }

        // Mengirim response JSON dengan data yang berhasil ditemukan
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'success',
            'message' => 'Data berhasil ditemukan.',
            'data' => $data
        ]);
    } else {
        // Mengirim response JSON ketika data tidak ditemukan
        header('Content-Type: application/json');
        echo json_encode([
            'status' => 'failed',
            'message' => 'Data tidak ditemukan.'
        ]);
    }
} else {
    // Mengirim response JSON ketika ID rincian pesanan tidak valid
    header('Content-Type: application/json');
    echo json_encode([
        'status' => 'failed',
        'message' => 'ID rincian pesanan tidak valid.'
    ]);
}

// Menutup koneksi ke database
$conn->close();
?>