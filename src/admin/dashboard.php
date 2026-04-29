<?php
session_start();
require_once '../config/koneksi.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header("Location: ../login.php");
    exit;
}

try {
    $queryUser = $conn->query("SELECT COUNT(*) as total FROM users");
    $userCount = $queryUser->fetch(PDO::FETCH_ASSOC)['total'];
} catch (PDOException $e) {
    $userCount = 0;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - BFI Finance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        /* CSS tetap sama seperti kode sebelumnya */
        :root {
            --primary-gold: #ffc107;
            --dark-bg: #0b1121;
            --sidebar-width: 280px;
            --glass-bg: rgba(255, 255, 255, 0.03);
            --glass-border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--dark-bg);
            color: #fff;
            margin: 0;
            overflow-x: hidden;
        }

       

        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            transition: all 0.3s ease;
        }

        .content-body {
            padding: 30px;
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            height: 100%;
            transition: 0.3s;
        }

        .stat-icon {
            width: 50px;
            height: 50px;
            background: rgba(255, 193, 7, 0.15);
            color: var(--primary-gold);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.4rem;
            margin-bottom: 15px;
        }

        @media (max-width: 991.98px) {
            .sidebar {
                transform: translateX(-100%);
            }

            .sidebar.show {
                transform: translateX(0);
                box-shadow: 20px 0 50px rgba(0, 0, 0, 0.8);
            }

            .main-content {
                margin-left: 0;
            }

            .sidebar-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0, 0, 0, 0.6);
                z-index: 1050;
                backdrop-filter: blur(4px);
            }

            .sidebar-overlay.active {
                display: block;
            }
        }

        .text-gold {
            color: var(--primary-gold);
        }

        .font-12 {
            font-size: 12px;
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?php include '../includes/admin/sidebar.php'; ?>
    <div class="main-content">
        <?php include '../includes/admin/topbar.php'; ?>
        <div class="content-body">
            <div class="mb-4">
                <h3 class="fw-bold mb-1">Halo, <?= explode(' ', $_SESSION['nama_admin'] ?? 'Admin')[0]; ?>! 👋</h3>
                <p class="opacity-50">Berikut adalah statistik sistem saat ini.</p>
            </div>

            <div class="row g-3 mb-4">
                <div class="col-6 col-md-4">
                    <div class="glass-card">
                        <div class="stat-icon"><i class="fa-solid fa-users"></i></div>
                        <h6 class="opacity-50 small mb-1">Total Admin</h6>
                        <h2 class="fw-bold mb-0 text-gold"><?= $userCount; ?></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        const toggleBtn = document.getElementById('toggleSidebar');
        const closeBtn = document.getElementById('closeSidebar');

        toggleBtn.onclick = () => {
            sidebar.classList.add('show');
            overlay.classList.add('active');
        };
        closeBtn.onclick = () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        };
        overlay.onclick = () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        };
    </script>
</body>

</html>