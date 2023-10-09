<?php

// Koneksi ke database
$dbhost = "localhost";
$dbuser = "id20844371_dapurbun";
$dbpass = "Sateayam1@";
$dbname = "id20844371_dapurbun";

$conn = new mysqli($dbhost, $dbuser, $dbpass, $dbname);

// Periksa koneksi
if ($conn->connect_error) {
    die("Koneksi ke database gagal: " . $conn->connect_error);
}

$response = array(); // Menyiapkan array untuk respons JSON

// Mendapatkan id_user dari POST
$id_user = $_POST['id_user'];

// Query untuk mendapatkan data dari tabel keranjang berdasarkan id_user
$query = "SELECT * FROM keranjang WHERE id_user = $id_user";
$result = $conn->query($query);

$rincian_pesanan_ids = array(); // Menyiapkan array untuk menyimpan id_rincian_pesanan

// Memasukkan data ke tabel rincian_pesanan
while ($row = $result->fetch_assoc()) {
    // Menyimpan data ke variabel-variabel
    $id_kategori_pesanan = $row['id_kategori_pesanan'];
    $id_produk = $row['id_produk'];
    $nama_produk = $row['nama_produk'];
    $deskripsi_produk = $row['deskripsi_produk'];
    $harga_produk = $row['harga_produk'];
    $jumlah_produk = $row['jumlah_produk'];
    $total_harga = $row['total_harga'];
    $catatan = $row['catatan'];
    $tanggal_booking = $row['tanggal_booking'];
    $waktu_booking = $row['waktu_booking'];

    // Query untuk memasukkan data ke tabel rincian_pesanan
    $insert_query = "INSERT INTO rincian_pesanan (id_kategori_pesanan, id_produk, nama_produk, deskripsi_produk, harga_produk, total_harga, catatan, jumlah_produk) 
                     VALUES ('$id_kategori_pesanan', '$id_produk', '$nama_produk', '$deskripsi_produk', '$harga_produk', '$total_harga', '$catatan', '$jumlah_produk')";

    if ($conn->query($insert_query) === FALSE) {
        $response = array(
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memasukkan data ke tabel rincian_pesanan: ' . $conn->error
        );
        echo json_encode($response);
        exit();
    }

    // Menyimpan id_rincian_pesanan yang baru saja dimasukkan ke array
    $rincian_pesanan_ids[] = $conn->insert_id;
}

// Mendapatkan data dari Flutter
$total_pembayaran = $_POST['total_pembayaran'];
$status_pembayaran = $_POST['status_pembayaran'];

// Query untuk memasukkan data ke tabel pembayaran
$insert_query = "INSERT INTO pembayaran (total_pembayaran, status_pembayaran) 
                 VALUES ('$total_pembayaran', '$status_pembayaran')";

if ($conn->query($insert_query) === FALSE) {
    $response = array(
        'status' => 'error',
        'message' => 'Terjadi kesalahan saat memasukkan data ke tabel pembayaran: ' . $conn->error
    );
    echo json_encode($response);
    exit();
}

// Mendapatkan id_pembayaran yang baru saja dimasukkan
$id_pembayaran = $conn->insert_id; // Mengambil ID terakhir yang di-generate oleh tabel pembayaran

$pesansql = "SELECT * FROM keranjang WHERE id_user = $id_user";

$pesanresult = $conn->query($pesansql);

if ($pesanresult->num_rows > 0) {
    while ($pesanrow = $pesanresult->fetch_assoc()) {
        // Masukkan data ke dalam tabel pemesanan
        $id_user = $_POST['id_user'];
        $tanggal_booking = $pesanrow["tanggal_booking"];
        $waktu_booking = $pesanrow["waktu_booking"];
        $total_harga = $pesanrow["total_harga"];
        $catatan = $pesanrow["catatan"];
        $biaya_pengantaran = $_POST['biaya_pengantaran'];
        $kode_pesanan = generateKodePesanan(); // Fungsi untuk menghasilkan kode pesanan
        $total_pesanan = $_POST['total_pesanan']; // Ganti dengan perhitungan yang sesuai
        $status_pesanan = $_POST['status_pesanan']; // Ganti dengan status pesanan yang sesuai
        $nama_penerima = $_POST['nama_penerima'];
        $nomer_telepon_penerima = $_POST['nomer_telepon_penerima'];
        $alamat_pengantaran = $_POST['alamat_pengantaran'];
        $total_pembayaran = $_POST['total_pembayaran'];

        $id_rincian_pesanan_array = implode(',', $rincian_pesanan_ids); // Mengubah array id_rincian_pesanan menjadi string dengan pemisah koma
        
        $sqlpesan = "INSERT INTO pemesanan (id_user, id_rincian_pesanan, id_pembayaran, kode_pesanan, tanggal_booking, waktu_booking, total_pesanan, catatan, nama_penerima, nomer_telepon_penerima, alamat_pengantaran, biaya_pengantaran, total_pembayaran, status_pesanan) VALUES ($id_user, '$id_rincian_pesanan_array', $id_pembayaran, '$kode_pesanan', '$tanggal_booking', '$waktu_booking', $total_pesanan, '$catatan', '$nama_penerima', '$nomer_telepon_penerima', '$alamat_pengantaran', '$biaya_pengantaran', '$total_pembayaran','$status_pesanan')";
    }
    if ($conn->query($sqlpesan) === TRUE) {
        $hapus = "DELETE FROM keranjang WHERE id_user = $id_user";
        $result1 = $conn->query($hapus);

        $response = array(
            'status' => 'success',
            'message' => 'Data berhasil dimasukkan ke tabel pemesanan.'
        );
        echo json_encode($response);
    } else {
        $response = array(
            'status' => 'error',
            'message' => 'Terjadi kesalahan saat memasukkan data ke tabel pemesanan: ' . $conn->error
        );
        echo json_encode($response);
    }
}

// Menutup koneksi ke database
$conn->close();

/**
 * Fungsi untuk menghasilkan kode pesanan unik
 */
function generateKodePesanan()
{
    $kode_pesanan = "DPRBND"; // Variabel untuk menyimpan kode pesanan

    // Lakukan pengambilan data dari database dan cek apakah kode_pesanan sudah ada atau belum
    // Jika sudah ada, generate kode_pesanan baru dan ulangi langkah ini
    // Jika belum ada, gunakan kode_pesanan yang sudah di-generate

    return $kode_pesanan;
}

?>
