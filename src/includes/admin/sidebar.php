<style>
    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        position: fixed;
        left: 0; top: 0;
        background: rgba(15, 23, 42, 0.98);
        backdrop-filter: blur(20px);
        border-right: 1px solid var(--glass-border);
        padding: 20px;
        z-index: 1100;
        transition: all 0.3s ease;
    }
    @media (max-width: 991px) {
        .sidebar { left: calc(var(--sidebar-width) * -1); }
        .sidebar.show { left: 0; }
    }
    .nav-link {
        color: rgba(255, 255, 255, 0.6);
        padding: 12px 15px; border-radius: 12px;
        margin-bottom: 5px; display: flex; align-items: center;
        text-decoration: none; transition: 0.3s;
    }
    .nav-link:hover, .nav-link.active {
        background: rgba(255, 193, 7, 0.15); color: var(--primary-gold);
    }
</style>

<div class="sidebar d-flex flex-column" id="mainSidebar">
    <div class="d-lg-none text-end mb-3">
        <button class="btn text-white opacity-50" id="closeSidebar">
            <i class="fa-solid fa-xmark fs-3"></i>
    </div>
    <div class="brand-logo text-center mb-4 border-bottom border-white border-opacity-10 pb-3">
        <h4 class="fw-bold mb-0">BFI <span class="text-warning">ADMIN</span></h4>
    </div>
    <nav class="flex-grow-1">
        <a href="dashboard.php" class="nav-link"><i class="fa-solid fa-house me-2"></i> Dashboard</a>
        <a href="unit_kendaraan.php" class="nav-link active"><i class="fa-solid fa-car me-2"></i> Unit Kendaraan</a>
        <a href="merk_kendaraan.php" class="nav-link"><i class="fa-solid fa-tags me-2"></i> Kelola Merk</a>
    </nav>
    <div class="pt-3 border-top border-white border-opacity-10">
        <a href="../logout.php" class="nav-link text-danger" id="btn-logout"><i class="fa-solid fa-power-off me-2"></i> Keluar</a>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.getElementById('mainSidebar');
    const overlay = document.getElementById('sidebarOverlay');
    const btnOpen = document.getElementById('sidebar-toggle');
    const btnClose = document.getElementById('closeSidebar');

    // Fungsi Buka
    function openSidebar() {
        sidebar.classList.add('show');
        if (overlay) overlay.classList.add('active');
        document.body.style.overflow = 'hidden'; // Mencegah scroll saat sidebar buka
    }

    // Fungsi Tutup
    function closeSidebar() {
        sidebar.classList.remove('show');
        if (overlay) overlay.classList.remove('active');
        document.body.style.overflow = 'auto';
    }

    // Listener Tombol Buka (Burger)
    if (btnOpen) {
        btnOpen.addEventListener('click', function(e) {
            e.preventDefault();
            openSidebar();
        });
    }

    // Listener Tombol Tutup (X)
    if (btnClose) {
        btnClose.addEventListener('click', function(e) {
            e.preventDefault();
            closeSidebar();
        });
    }

    // Listener Klik Overlay (Klik di luar sidebar untuk menutup)
    if (overlay) {
        overlay.addEventListener('click', closeSidebar);
    }
});
</script>