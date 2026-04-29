<?php
// modules/kendaraan/get-tipe.php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "db_bfi";

try {
    $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $type = $_GET['type'] ?? '';
    $id_kategori = $_GET['id_kategori'] ?? '';
    $merk = $_GET['merk'] ?? '';

    header('Content-Type: application/json');

    if ($type == 'merk') {
        $stmt = $conn->prepare("SELECT DISTINCT merk FROM unit_kendaraan WHERE id_kategori = ? ORDER BY merk ASC");
        $stmt->execute([$id_kategori]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_COLUMN));
    } elseif ($type == 'unit') {
        $stmt = $conn->prepare("SELECT model, harga_pasar FROM unit_kendaraan WHERE id_kategori = ? AND merk = ? ORDER BY model ASC");
        $stmt->execute([$id_kategori, $merk]);
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode(['error' => $e->getMessage()]);
}
