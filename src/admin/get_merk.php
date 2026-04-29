<?php
// 1. Set header paling atas tanpa ada karakter/spasi sebelumnya
header('Content-Type: application/json');

// 2. Pastikan path ini benar sesuai struktur folder di Docker /var/www/html
// Jika file ini di /admin/get_merk.php dan koneksi di /config/koneksi.php, gunakan ../
require_once '../config/koneksi.php'; 

if (isset($_GET['kategori_id']) && !empty($_GET['kategori_id'])) {
    $kategori_id = $_GET['kategori_id'];

    try {
        // Ambil data merk
        $stmt = $conn->prepare("SELECT id, nama_merk FROM merk_kendaraan WHERE kategori_id = ? ORDER BY nama_merk ASC");
        $stmt->execute([$kategori_id]);

        $merks = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Selalu kirimkan array, meskipun kosong []
        echo json_encode($merks ? $merks : []);
        
    } catch (PDOException $e) {
        http_response_code(500);
        // Di Docker, log ini akan muncul di 'docker logs bfi_finance-web-1'
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(400); // Bad Request
    echo json_encode(['message' => 'Kategori ID diperlukan']);
}
exit; // Pastikan tidak ada output lain setelah JSON