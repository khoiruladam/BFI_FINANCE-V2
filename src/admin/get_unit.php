<?php
header('Content-Type: application/json');

// Pastikan path ini benar!
require_once '../config/koneksi.php'; 

if (isset($_GET['merk_id']) && !empty($_GET['merk_id'])) {
    $merk_id = $_GET['merk_id'];

    try {
        // Ambil data unit beserta harga_pasar untuk kalkulasi simulasi
        $sql = "SELECT id, nama_unit, model, harga_pasar FROM unit_kendaraan WHERE merk_id = ? ORDER BY nama_unit ASC";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$merk_id]);
        $units = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Mengirim array kosong jika tidak ada data, agar loop di JS tidak error
        echo json_encode($units ?: []);
        
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error', 'details' => $e->getMessage()]);
    }
} else {
    http_response_code(400);
    echo json_encode(['error' => 'Parameter merk_id diperlukan']);
}
exit;