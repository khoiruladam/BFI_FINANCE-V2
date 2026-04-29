<?php
session_start();
require_once '../config/koneksi.php';

if (isset($_POST['simpan_merk'])) {
    $id = $_POST['id_merk'];
    $kategori_id = $_POST['kategori_id'];
    $nama_merk = $_POST['nama_merk'];

    if ($_FILES['logo']['name'] != "") {
        $logo_name = $_FILES['logo']['name'];
        $tmp_name = $_FILES['logo']['tmp_name'];
        $extension = pathinfo($logo_name, PATHINFO_EXTENSION);
        $final_name = "logo_" . time() . "." . $extension;
        move_uploaded_file($tmp_name, "../assets/img/merek/" . $final_name);
        $logo_sql = ", logo = '$final_name'";
    } else {
        $final_name = $_POST['logo_lama'];
        $logo_sql = "";
    }

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO merk_kendaraan (kategori_id, nama_merk, logo) VALUES (?, ?, ?)");
        $stmt->execute([$kategori_id, $nama_merk, $final_name]);
        $_SESSION['msg'] = 'added';
    } else {
        $sql = "UPDATE merk_kendaraan SET kategori_id = ?, nama_merk = ? $logo_sql WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$kategori_id, $nama_merk, $id]);
        $_SESSION['msg'] = 'updated';
    }
    header("Location: merk_kendaraan.php");
    exit;
}

if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $conn->prepare("DELETE FROM merk_kendaraan WHERE id = ?")->execute([$id]);
    $_SESSION['msg'] = 'deleted';
    header("Location: merk_kendaraanphp");
    exit;
}

$kategori_list = $conn->query("SELECT * FROM kategori_kendaraan")->fetchAll(PDO::FETCH_ASSOC);
$merks = $conn->query("SELECT m.*, k.nama_kategori 
                       FROM merk_kendaraan m 
                       JOIN kategori_kendaraan k ON m.kategori_id = k.id 
                       ORDER BY m.nama_merk ASC")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Merk - Premium Admin</title>
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
        }

        .main-content {
            margin-left: var(--sidebar-width);
            padding: 30px;
            transition: 0.3s;
            min-height: 100vh;
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            padding: 25px;
            backdrop-filter: blur(10px);
        }

        .img-logo-table {
            width: 50px;
            height: 50px;
            object-fit: contain;
            background: #fff;
            padding: 5px;
            border-radius: 10px;
        }

        /* Sidebar Mobile & Overlay */
        .sidebar-overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(4px);
        }

        .sidebar-overlay.active {
            display: block;
        }

        .modal-content {
            background: #161f33;
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 20px;
        }

        .form-control,
        .form-select {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid var(--glass-border);
            color: #fff;
            border-radius: 12px;
        }

        .form-control:focus,
        .form-select:focus {
            background: rgba(255, 255, 255, 0.1);
            border-color: var(--primary-gold);
            box-shadow: none;
            color: #fff;
        }

        @media (max-width: 991px) {
            .main-content {
                margin-left: 0;
            }

            .sidebar {
                transform: translateX(-100%);
                transition: 0.3s;
            }

            .sidebar.show {
                transform: translateX(0);
                z-index: 1050;
            }
        }
    </style>
</head>

<body>

    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <?php include '../includes/admin/sidebar.php'; ?>

    <div class="main-content">
        <button class="btn btn-dark d-lg-none mb-3" id="btnToggle"><i class="fa-solid fa-bars"></i></button>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h3 class="fw-bold mb-1">Master Merk Kendaraan</h3>
                <p class="opacity-50 small">Kelola data merk dengan sistem Cloud Storage lokal.</p>
            </div>
            <button class="btn btn-warning rounded-pill px-4 fw-bold" onclick="tambahMerk()">
                <i class="fa-solid fa-plus me-2"></i> Tambah Merk
            </button>
        </div>

        <div class="glass-card">
            <div class="table-responsive">
                <table class="table table-dark table-borderless align-middle">
                    <thead>
                        <tr class="text-uppercase opacity-50 border-bottom border-white border-opacity-10 small">
                            <th>Logo</th>
                            <th>Nama Merk</th>
                            <th>Kategori</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($merks as $m): ?>
                            <tr>
                                <td><img src="../assets/img/merek/<?= $m['logo']; ?>" class="img-logo-table"></td>
                                <td class="fw-bold"><?= htmlspecialchars($m['nama_merk']); ?></td>
                                <td><span class="badge bg-warning bg-opacity-10 text-warning px-3"><?= $m['nama_kategori']; ?></span></td>
                                <td class="text-end">
                                    <button class="btn btn-sm btn-outline-info border-0 me-2" onclick='editMerk(<?= json_encode($m); ?>)'>
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger border-0" onclick="konfirmasiHapus(<?= $m['id']; ?>)">
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

    <div class="modal fade" id="modalMerk" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold" id="modalTitle">Tambah Merk</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <form action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_merk" id="id_merk">
                    <input type="hidden" name="logo_lama" id="logo_lama">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="small mb-2 opacity-50">Kategori</label>
                            <select name="kategori_id" id="kategori_id" class="form-select" required>
                                <option value="">-- Pilih Kategori --</option>
                                <?php foreach ($kategori_list as $k): ?>
                                    <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-2 opacity-50">Nama Merk</label>
                            <input type="text" name="nama_merk" id="nama_merk" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="small mb-2 opacity-50">Logo (Biarkan kosong jika tidak ganti)</label>
                            <input type="file" name="logo" class="form-control" accept="image/*">
                        </div>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="simpan_merk" class="btn btn-warning w-100 rounded-pill py-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        const myModal = new bootstrap.Modal(document.getElementById('modalMerk'));
        const sidebar = document.querySelector('.sidebar');
        const overlay = document.getElementById('sidebarOverlay');

        // Sidebar Toggle
        document.getElementById('btnToggle')?.addEventListener('click', () => {
            sidebar.classList.add('show');
            overlay.classList.add('active');
        });

        overlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            overlay.classList.remove('active');
        });

        function tambahMerk() {
            document.getElementById('id_merk').value = '';
            document.getElementById('modalTitle').innerText = 'Tambah Merk Baru';
            document.getElementById('nama_merk').value = '';
            document.getElementById('kategori_id').value = '';
            myModal.show();
        }

        function editMerk(data) {
            document.getElementById('id_merk').value = data.id;
            document.getElementById('logo_lama').value = data.logo;
            document.getElementById('kategori_id').value = data.kategori_id;
            document.getElementById('nama_merk').value = data.nama_merk;
            document.getElementById('modalTitle').innerText = 'Edit Merk: ' + data.nama_merk;
            myModal.show();
        }

        function konfirmasiHapus(id) {
            Swal.fire({
                title: 'Apakah anda yakin?',
                text: "Data unit yang berhubungan dengan merk ini juga akan hilang!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ffc107',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Hapus!',
                background: '#161f33',
                color: '#fff'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = `merk_kendaraan.php?action=delete&id=${id}`;
                }
            })
        }

        <?php if (isset($_SESSION['msg'])): ?>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Data telah diproses.',
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