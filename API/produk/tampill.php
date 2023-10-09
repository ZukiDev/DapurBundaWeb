<?php
// Koneksi ke database
$host = 'localhost';
$db   = 'id20844371_dapurbun';
$user = 'id20844371_dapurbun';
$pass = 'Sateayam1@';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

// Query database untuk mendapatkan data produk
$query = "SELECT * FROM produk";
$stmt = $pdo->query($query);
$produkData = $stmt->fetchAll();

// Mengubah data produk menjadi format JSON
$jsonData = json_encode($produkData);

// Menampilkan data JSON
header('Content-Type: application/json');
echo $jsonData;
?>
