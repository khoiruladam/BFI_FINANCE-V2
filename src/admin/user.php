<?php
session_start();
require_once '../config/koneksi.php';

// Proteksi Login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

// --- LOGIKA CRUD ---
if (isset($_POST['simpan_user'])) {
    $id = $_POST['id_user'];
    $username = $_POST['username'];
    $nama_lengkap = $_POST['nama_lengkap'];
    $password = $_POST['password'];

    if (empty($id)) {
        // Tambah User Baru
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, password, nama_lengkap) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$username, $hashed_password, $nama_lengkap]);
        $_SESSION['msg'] = 'added';
    } else {
        // Edit User
        if (!empty($password)) {
            // Jika password diisi, update password juga
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = ?, password = ?, nama_lengkap = ? WHERE id = ?";
            $params = [$username, $hashed_password, $nama_lengkap, $id];
        } else {
            // Jika password kosong, jangan update password
            $sql = "UPDATE users SET username = ?, nama_lengkap = ? WHERE id = ?";
            $params = [$username, $nama_lengkap, $id];
        }
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);
        $_SESSION['msg'] = 'updated';
    }
    header("Location: user.php");
    exit;
}

// Hapus User
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $conn->prepare("DELETE FROM users WHERE id = ?")->execute([$id]);
    $_SESSION['msg'] = 'deleted';
    header("Location: user_management.php");
    exit;
}

// Ambil Data Users
$users = $conn->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Management - Premium Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
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
            overflow-x: hidden;
        }

        /* Sidebar & Content Logic */
        .sidebar {
            width: var(--sidebar-width);
            position: fixed;
            left: 0;
            top: 0;
            height: 100vh;
            transition: 0.3s;
            z-index: 1045;
        }

        body.sidebar-toggled .sidebar {
            left: calc(var(--sidebar-width) * -1);
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: 0.3s;
        }

        body.sidebar-toggled .main-content {
            margin-left: 0;
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
        }

        .form-control {
            background: #1e293b !important;
            border: 1px solid var(--glass-border);
            color: #fff !important;
            border-radius: 12px;
        }

        .modal-content {
            background: #161f33;
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 20px;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                left: calc(var(--sidebar-width) * -1);
            }

            .sidebar.show {
                left: 0;
            }
        }
    </style>
</head>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?php include '../includes/admin/sidebar.php'; ?>

    <div class="main-content">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div class="d-flex align-items-center gap-3">
                <button id="sidebar-toggle" class="btn text-warning shadow-none">
                    <i class="fa-solid fa-bars fa-lg"></i>
                </button>
                <div>
                    <h3 class="fw-bold mb-1">User Management</h3>
                    <p class="opacity-50 small">Kelola hak akses administrator sistem.</p>
                </div>
            </div>
            <button class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm" onclick="tambahUser()">
                <i class="fa-solid fa-user-plus me-2"></i> Tambah User
            </button>
        </div>

        <div class="glass-card">
            <div class="table-responsive">
                <table class="table table-dark table-borderless align-middle">
                    <thead>
                        <tr class="text-uppercase opacity-50 border-bottom border-white border-opacity-10 small">
                            <th>Nama Lengkap</th>
                            <th>Username</th>
                            <th>Dibuat Pada</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $u): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="bg-warning text-dark rounded-circle d-flex align-items-center justify-content-center" style="width: 40px; height: 40px; font-weight: 800;">
                                            <?= strtoupper(substr($u['nama_lengkap'], 0, 1)); ?>
                                        </div>
                                        <div class="fw-bold"><?= $u['nama_lengkap']; ?></div>
                                    </div>
                                </td>
                                <td><code class="text-info">@<?= $u['username']; ?></code></td>
                                <td class="small opacity-75"><?= date('d M Y, H:i', strtotime($u['created_at'])); ?></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info border-0 me-2" onclick='editUser(<?= json_encode($u); ?>)'>
                                        <i class="fa-solid fa-user-pen"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="konfirmasiHapus(<?= $u['id']; ?>)">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Admin</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST">
                    <input type="hidden" name="id_user" id="id_user">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-1 text-white-50">Nama Lengkap</label>
                            <input type="text" name="nama_lengkap" id="m_nama" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-white-50">Username</label>
                            <input type="text" name="username" id="m_username" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-1 text-white-50">Password</label>
                            <input type="password" name="password" id="m_password" class="form-control" placeholder="Kosongkan jika tidak ingin ganti">
                            <small class="text-warning d-none" id="passHint">*Isi hanya jika ingin mengganti password</small>
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="simpan_user" class="btn btn-warning w-100 rounded-pill py-2 fw-bold">SIMPAN PERUBAHAN</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const userModal = new bootstrap.Modal(document.getElementById('modalUser'));

        // Sidebar Toggle Logic
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            document.body.classList.toggle('sidebar-toggled');
            const sidebar = document.querySelector('.sidebar');
            if (window.innerWidth <= 991) {
                sidebar.classList.toggle('show');
            }
        });

        function tambahUser() {
            document.getElementById('id_user').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Admin Baru';
            document.getElementById('m_password').required = true;
            document.getElementById('passHint').classList.add('d-none');
            document.querySelector('form').reset();
            userModal.show();
        }

        function editUser(data) {
            document.getElementById('id_user').value = data.id;
            document.getElementById('modalTitle').innerText = 'Edit User: ' + data.username;
            document.getElementById('m_nama').value = data.nama_lengkap;
            document.getElementById('m_username').value = data.username;
            document.getElementById('m_password').required = false; // Saat edit, password tidak wajib
            document.getElementById('passHint').classList.remove('d-none');
            userModal.show();
        }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Hapus User?',
                text: "User ini tidak akan bisa login lagi!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                background: '#161f33',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `user.php?action=delete&id=${id}`;
                }
            })
        }

        // Alert Berhasil
        <?php if (isset($_SESSION['msg'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                background: '#161f33',
                color: '#fff',
                showConfirmButton: false,
                timer: 1500
            });
            <?php unset($_SESSION['msg']); ?>
        <?php endif; ?>
    </script>
</body>

</html>