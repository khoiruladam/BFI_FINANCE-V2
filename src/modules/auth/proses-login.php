<?php
session_start();

$path_koneksi = "../../config/koneksi.php";

if (file_exists($path_koneksi)) {
    include $path_koneksi;
} else {
    die("File koneksi tidak ditemukan.");
}

if (isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    try {
        $query = "SELECT * FROM users WHERE username = :user";
        $stmt  = $conn->prepare($query);
        $stmt->execute(['user' => $username]);
        $row   = $stmt->fetch();

        if ($row) {
            if ($password == $row['password']) {
                $_SESSION['admin_logged_in'] = true;

                // UBAH BAGIAN INI:
                $_SESSION['nama_lengkap'] = $row['nama_lengkap'];
                $_SESSION['username']     = $row['username'];

                header("Location: /admin/dashboard.php");
                exit;
            }
        }
        
        $_SESSION['error'] = "Username atau password salah!";
        header("Location: ../../login.php");
        exit;
    } catch (PDOException $e) {
        die("Error pada database: " . $e->getMessage());
    }
} else {
    header("Location: ../../login.php");
    exit;
}
