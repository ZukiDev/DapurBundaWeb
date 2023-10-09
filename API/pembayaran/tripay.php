<?php
$apiKey = "DEV-m9xT4t8yjc2eEM1UZZ31KWltvXJUaRscrwzM2kC6"; // Ganti dengan API Key Tripay Anda

// Mengambil data yang dikirimkan dari aplikasi Flutter
$data = json_decode(file_get_contents('php://input'), true);

// Data yang dikirim dari aplikasi Flutter
$orderId = $data['order_id'] ?? '';
$amount = $data['amount'] ?? '';

if ($orderId !== '' && $amount !== '') {
    // Buat payload untuk permintaan pembayaran
    $payload = array(
        'method' => 'payment',
        'merchant_ref' => $orderId,
        'amount' => $amount,
        'customer_name' => 'John Doe', // Ganti dengan nama pelanggan yang sesuai
        'customer_email' => 'johndoe@example.com', // Ganti dengan alamat email pelanggan yang sesuai
        'customer_phone' => '081234567890', // Ganti dengan nomor telepon pelanggan yang sesuai
        'order_items' => array(
            array(
                'sku' => 'ITEM001', // Ganti dengan SKU barang yang sesuai
                'name' => 'Product 1', // Ganti dengan nama barang yang sesuai
                'price' => 100000, // Ganti dengan harga barang yang sesuai
                'quantity' => 1 // Ganti dengan jumlah barang yang sesuai
            )
        )
    );

    // Konversi payload menjadi JSON
    $payloadJson = json_encode($payload);

    // Buat signature dengan menggunakan API Key Tripay dan payload JSON
    $signature = hash_hmac('sha256', $payloadJson, $apiKey);

    // Kirim permintaan pembayaran ke API Tripay
    $ch = curl_init('https://tripay.co.id/api/payment');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payloadJson);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'Authorization: Bearer ' . $apiKey,
        'Content-Type: application/json',
        'Accept: application/json',
        'Signature: ' . $signature
    ));

    $result = curl_exec($ch);
    curl_close($ch);

    // Mengirimkan respons ke aplikasi Flutter
    header('Content-Type: application/json');
    echo $result;
} else {
    echo json_encode(array(
        'success' => false,
        'message' => 'Invalid data'
    ));
}
?>
