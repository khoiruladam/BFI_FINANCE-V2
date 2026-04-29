<nav class="navbar navbar-expand-lg navbar-dark fixed-top glass-nav">
    <div class="container">
        <a class="navbar-brand fw-bold" href="index.php">
            <i class="fa-solid fa-university text-warning me-2"></i>BFI FINANCE
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">
                <li class="nav-item"><a class="nav-link" href="index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link active" href="#">Simulasi</a></li>

                <li class="nav-item ms-lg-3">
                    <?php if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true): ?>
                        <a class="nav-link btn-login-glass" href="admin/dashboard.php">
                            <i class="fa-solid fa-gauge me-2"></i>Dashboard
                        </a>
                    <?php else: ?>
                        <a class="nav-link btn-login-glass" href="login.php">
                            <i class="fa-solid fa-right-to-bracket me-2"></i>Login Admin
                        </a>
                    <?php endif; ?>
                </li>
            </ul>
        </div>
    </div>
</nav>