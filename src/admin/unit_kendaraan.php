<?php
session_start();
require_once '../config/koneksi.php';

// Proteksi Login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: ../login.php");
    exit;
}

// --- LOGIKA IMPORT CSV ---
if (isset($_POST['import_csv'])) {
    if ($_FILES['file_csv']['name']) {
        $filename = $_FILES['file_csv']['tmp_name'];
        $file = fopen($filename, "r");
        
        // Skip header baris pertama
        fgetcsv($file); 

        $conn->beginTransaction();
        try {
            while (($column = fgetcsv($file, 10000, ",")) !== FALSE) {
                // Urutan CSV: merk_id, nama_unit, model, tahun, harga
                $sql = "INSERT INTO unit_kendaraan (merk_id, nama_unit, model, tahun_keluaran, harga_pasar) VALUES (?, ?, ?, ?, ?)";
                $stmt = $conn->prepare($sql);
                $stmt->execute([$column[0], $column[1], $column[2], $column[3], $column[4]]);
            }
            $conn->commit();
            $_SESSION['msg'] = 'imported';
        } catch (Exception $e) {
            $conn->rollBack();
            $_SESSION['msg_error'] = 'Gagal import: ' . $e->getMessage();
        }
        fclose($file);
        header("Location: unit_kendaraan.php");
        exit;
    }
}

// --- LOGIKA CRUD (Simpan/Update) ---
if (isset($_POST['simpan_unit'])) {
    $id = $_POST['id_unit'];
    $merk_id = $_POST['merk_id'];
    $nama_unit = $_POST['nama_unit'];
    $model = $_POST['model'];
    $tahun = $_POST['tahun_keluaran'];
    $harga = $_POST['harga_pasar'];

    if (empty($id)) {
        $sql = "INSERT INTO unit_kendaraan (merk_id, nama_unit, model, tahun_keluaran, harga_pasar) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$merk_id, $nama_unit, $model, $tahun, $harga]);
        $_SESSION['msg'] = 'added';
    } else {
        $sql = "UPDATE unit_kendaraan SET merk_id = ?, nama_unit = ?, model = ?, tahun_keluaran = ?, harga_pasar = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->execute([$merk_id, $nama_unit, $model, $tahun, $harga, $id]);
        $_SESSION['msg'] = 'updated';
    }
    header("Location: unit_kendaraan.php");
    exit;
}

// --- LOGIKA DELETE ---
if (isset($_GET['action']) && $_GET['action'] == 'delete') {
    $id = $_GET['id'];
    $conn->prepare("DELETE FROM unit_kendaraan WHERE id = ?")->execute([$id]);
    $_SESSION['msg'] = 'deleted';
    header("Location: unit_kendaraan.php");
    exit;
}

// --- LOGIKA FILTER & QUERY ---
$filter_kategori = $_GET['f_kategori'] ?? '';
$filter_merk_text = $_GET['f_merk_text'] ?? '';

$queryStr = "SELECT u.*, m.nama_merk, m.logo, m.kategori_id, k.nama_kategori 
             FROM unit_kendaraan u 
             JOIN merk_kendaraan m ON u.merk_id = m.id 
             JOIN kategori_kendaraan k ON m.kategori_id = k.id WHERE 1=1";

$params = [];
if ($filter_kategori) {
    $queryStr .= " AND m.kategori_id = ?";
    $params[] = $filter_kategori;
}
if ($filter_merk_text) {
    $queryStr .= " AND m.nama_merk LIKE ?";
    $params[] = "%$filter_merk_text%";
}
$queryStr .= " ORDER BY u.created_at DESC";

$stmtUnit = $conn->prepare($queryStr);
$stmtUnit->execute($params);
$units = $stmtUnit->fetchAll(PDO::FETCH_ASSOC);

$kategori_list = $conn->query("SELECT * FROM kategori_kendaraan")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Management Unit - BFI Admin</title>
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

        /* Responsive Layout */
        .main-content {
            margin-left: 0;
            padding: 15px;
            transition: 0.3s;
        }

        @media (min-width: 992px) {
            .main-content { margin-left: var(--sidebar-width); padding: 30px; }
            body.sidebar-toggled .main-content { margin-left: 0; }
        }

        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            border-radius: 20px;
            backdrop-filter: blur(10px);
            padding: 20px;
        }

        .form-control, .form-select {
            background: #1e293b !important;
            border: 1px solid var(--glass-border);
            color: #fff !important;
            border-radius: 12px;
        }

        .img-logo-table {
            width: 40px; height: 40px; object-fit: contain;
            background: white; padding: 4px; border-radius: 8px;
        }

        /* Mobile Table Transformation */
        @media (max-width: 768px) {
            .table-responsive thead { display: none; }
            .table-responsive tbody tr {
                display: block; background: var(--glass-bg);
                margin-bottom: 15px; border-radius: 15px; padding: 10px;
                border: 1px solid var(--glass-border);
            }
            .table-responsive tbody td {
                display: flex; justify-content: space-between; align-items: center;
                border: none; padding: 8px 10px; text-align: right;
            }
            .table-responsive tbody td::before {
                content: attr(data-label); font-weight: bold; color: var(--primary-gold);
            }
        }

        .sidebar-overlay {
            position: fixed; inset: 0; background: rgba(0,0,0,0.5);
            display: none; z-index: 1040; backdrop-filter: blur(4px);
        }
        .sidebar-overlay.active { display: block; }
    </style>
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<?php include '../includes/admin/sidebar.php'; ?>

<div class="main-content">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-4 gap-3">
        <div class="d-flex align-items-center gap-3">
            <button id="sidebar-toggle" class="btn btn-outline-warning border-0">
                <i class="fa-solid fa-bars-staggered fs-4"></i>
            </button>
            <div>
                <h3 class="fw-bold mb-0">Unit Kendaraan</h3>
                <p class="opacity-50 small mb-0">Manajemen data stok unit BFI Finance.</p>
            </div>
        </div>
        <div class="d-flex gap-2 w-100 w-md-auto">
            <button class="btn btn-outline-info rounded-pill px-3 fw-bold flex-fill" data-bs-toggle="modal" data-bs-target="#modalImport">
                <i class="fa-solid fa-file-import me-1"></i> Import
            </button>
            <button class="btn btn-warning rounded-pill px-3 fw-bold flex-fill" onclick="tambahUnit()">
                <i class="fa-solid fa-plus me-1"></i> Tambah
            </button>
        </div>
    </div>

    <div class="glass-card mb-4">
        <form method="GET" class="row g-2">
            <div class="col-6 col-md-4">
                <select name="f_kategori" class="form-select" onchange="this.form.submit()">
                    <option value="">Semua Kategori</option>
                    <?php foreach ($kategori_list as $k): ?>
                        <option value="<?= $k['id']; ?>" <?= $filter_kategori == $k['id'] ? 'selected' : ''; ?>><?= $k['nama_kategori']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-6 col-md-4">
                <input type="text" name="f_merk_text" class="form-control" placeholder="Cari Merk..." value="<?= htmlspecialchars($filter_merk_text); ?>">
            </div>
            <div class="col-12 col-md-4 d-flex gap-2">
                <button type="submit" class="btn btn-primary flex-fill rounded-pill">Cari</button>
                <a href="unit_kendaraan.php" class="btn btn-danger flex-fill rounded-pill">Reset</a>
            </div>
        </form>
    </div>

    <div class="glass-card">
        <div class="table-responsive">
            <table class="table table-dark table-borderless align-middle mb-0">
                <thead>
                    <tr class="opacity-50 small border-bottom border-white border-opacity-10">
                        <th>Unit</th>
                        <th>Merk</th>
                        <th>Tahun</th>
                        <th>Harga</th>
                        <th class="text-end">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($units as $u): ?>
                    <tr>
                        <td data-label="Unit">
                            <div class="d-flex align-items-center gap-3">
                                <img src="../assets/img/merek/<?= $u['logo']; ?>" class="img-logo-table d-none d-md-block">
                                <div>
                                    <div class="fw-bold"><?= $u['nama_unit']; ?></div>
                                    <div class="small opacity-50"><?= $u['model']; ?></div>
                                </div>
                            </div>
                        </td>
                        <td data-label="Merk">
                            <span class="badge bg-warning text-dark"><?= $u['nama_merk']; ?></span>
                        </td>
                        <td data-label="Tahun"><?= $u['tahun_keluaran']; ?></td>
                        <td data-label="Harga" class="text-success fw-bold">Rp <?= number_format($u['harga_pasar'], 0, ',', '.'); ?></td>
                        <td data-label="Aksi" class="text-end">
                            <button class="btn btn-sm text-info" onclick='editUnit(<?= json_encode($u); ?>)'><i class="fa-solid fa-edit"></i></button>
                            <button class="btn btn-sm text-danger" onclick="konfirmasiHapus(<?= $u['id']; ?>)"><i class="fa-solid fa-trash"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="modalImport" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card p-0" style="background:#161f33;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold">Import Data CSV</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="small text-white-50">Gunakan format file .csv dengan urutan kolom: <br> <strong>merk_id, nama_unit, model, tahun, harga_pasar</strong></p>
                    <input type="file" name="file_csv" class="form-control" accept=".csv" required>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="import_csv" class="btn btn-info w-100 rounded-pill fw-bold">IMPORT SEKARANG</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="modalUnit" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content glass-card p-0" style="background:#161f33;">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="modalTitle">Tambah Unit</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <form action="" method="POST">
                <input type="hidden" name="id_unit" id="id_unit">
                <div class="modal-body row g-3">
                    <div class="col-12">
                        <label class="small mb-1 opacity-50">Kategori</label>
                        <select id="m_kategori" class="form-select" required onchange="loadMerk(this.value)">
                            <option value="">-- Pilih Kategori --</option>
                            <?php foreach ($kategori_list as $k): ?>
                                <option value="<?= $k['id']; ?>"><?= $k['nama_kategori']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12">
                        <label class="small mb-1 opacity-50">Merk Kendaraan</label>
                        <select name="merk_id" id="m_merk" class="form-select" required disabled></select>
                    </div>
                    <div class="col-6"><input type="text" name="nama_unit" id="m_nama" class="form-control" placeholder="Nama Unit" required></div>
                    <div class="col-6"><input type="text" name="model" id="m_model" class="form-control" placeholder="Model" required></div>
                    <div class="col-6"><input type="number" name="tahun_keluaran" id="m_tahun" class="form-control" placeholder="Tahun" required></div>
                    <div class="col-6"><input type="number" name="harga_pasar" id="m_harga" class="form-control" placeholder="Harga Pasar" required></div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" name="simpan_unit" class="btn btn-warning w-100 rounded-pill fw-bold">SIMPAN DATA</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const unitModal = new bootstrap.Modal(document.getElementById('modalUnit'));

    // --- Logika Sidebar Toggle ---
    document.getElementById('sidebar-toggle').addEventListener('click', function() {
        const sidebar = document.getElementById('mainSidebar');
        const overlay = document.getElementById('sidebarOverlay');
        if (window.innerWidth > 991) {
            document.body.classList.toggle('sidebar-toggled');
        } else {
            sidebar.classList.add('show');
            overlay.classList.add('active');
        }
    });

    document.getElementById('sidebarOverlay').addEventListener('click', function() {
        document.getElementById('mainSidebar').classList.remove('show');
        this.classList.remove('active');
    });

    // --- Logika Fetch Merk ---
    function loadMerk(kategoriId, selectedMerkId = null) {
        const selectMerk = document.getElementById('m_merk');
        if (!kategoriId) {
            selectMerk.disabled = true;
            selectMerk.innerHTML = '';
            return;
        }
        fetch(`get_merk.php?kategori_id=${kategoriId}`)
            .then(res => res.json())
            .then(data => {
                selectMerk.disabled = false;
                selectMerk.innerHTML = '<option value="">-- Pilih Merk --</option>';
                data.forEach(m => {
                    let sel = (selectedMerkId == m.id) ? 'selected' : '';
                    selectMerk.innerHTML += `<option value="${m.id}" ${sel}>${m.nama_merk}</option>`;
                });
            });
    }

    function tambahUnit() {
        document.getElementById('id_unit').value = '';
        document.getElementById('modalTitle').innerText = 'Tambah Unit Baru';
        document.querySelector('form').reset();
        unitModal.show();
    }

    function editUnit(data) {
        document.getElementById('id_unit').value = data.id;
        document.getElementById('modalTitle').innerText = 'Edit Unit';
        document.getElementById('m_nama').value = data.nama_unit;
        document.getElementById('m_model').value = data.model;
        document.getElementById('m_tahun').value = data.tahun_keluaran;
        document.getElementById('m_harga').value = data.harga_pasar;
        document.getElementById('m_kategori').value = data.kategori_id;
        loadMerk(data.kategori_id, data.merk_id);
        unitModal.show();
    }

    function konfirmasiHapus(id) {
        Swal.fire({
            title: 'Hapus data?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ffc107',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus!',
            background: '#161f33', color: '#fff'
        }).then((result) => {
            if (result.isConfirmed) window.location.href = `unit_kendaraan.php?action=delete&id=${id}`;
        });
    }

    <?php if (isset($_SESSION['msg'])): ?>
        Swal.fire({ icon: 'success', title: 'Berhasil!', background: '#161f33', color: '#fff', showConfirmButton: false, timer: 1500 });
        <?php unset($_SESSION['msg']); ?>
    <?php endif; ?>
</script>
</body>
</html>