<?php
session_start();

if (isset($_SESSION['admin_logged_in'])) {
    header("Location: admin/dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - BFI Finance</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    <style>
        :root {
            --primary-gold: #ffc107;
            --dark-bg: #0f172a;
        }

        body.login-page {
            font-family: 'Plus Jakarta Sans', sans-serif;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            background: var(--dark-bg);
            margin: 0;
            position: relative;
        }

        .grid-bg {
            position: absolute;
            inset: 0;
            background-image: linear-gradient(to right, rgba(255, 255, 255, 0.05) 1px, transparent 1px),
                linear-gradient(to bottom, rgba(255, 255, 255, 0.05) 1px, transparent 1px);
            background-size: 40px 40px;
            mask-image: radial-gradient(circle at center, black, transparent 80%);
            z-index: 1;
        }

        .login-container {
            width: 100%;
            max-width: 420px;
            padding: 20px;
            position: relative;
            z-index: 2;
        }

        .glass-card-login {
            background: rgba(255, 255, 255, 0.03);
            backdrop-filter: blur(25px);
            -webkit-backdrop-filter: blur(25px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 28px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        .login-logo {
            font-size: 1.75rem;
            font-weight: 800;
            color: #fff;
            text-align: center;
            margin-bottom: 35px;
            letter-spacing: -0.5px;
        }

        .highlight {
            color: var(--primary-gold);
        }

        .form-label {
            margin-left: 5px;
            font-weight: 600;
            color: #ffffff !important;
            opacity: 0.9;
        }

        .input-group {
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            transition: 0.3s;
        }

        .input-group:focus-within {
            border-color: var(--primary-gold);
            box-shadow: 0 0 0 4px rgba(255, 193, 7, 0.1);
        }

        .input-group-text {
            border: none;
            background: transparent !important;
            color: rgba(255, 255, 255, 0.5) !important;
            padding-left: 15px;
        }

        .form-control.custom-input {
            background: transparent !important;
            border: none !important;
            color: #ffffff !important;
            padding: 12px 15px 12px 5px;
            box-shadow: none !important;
        }

        .form-control::placeholder {
            color: rgba(255, 255, 255, 0.3) !important;
        }

        .btn-login {
            background: linear-gradient(135deg, #ffc107 0%, #ff9800 100%);
            border: none;
            border-radius: 12px;
            padding: 14px;
            font-weight: 700;
            color: #000;
            width: 100%;
            margin-top: 15px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            transition: 0.3s;
        }

        .btn-login:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(255, 193, 7, 0.4);
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 25px;
            color: rgba(255, 255, 255, 0.4);
            text-decoration: none;
            font-size: 0.85rem;
            transition: 0.3s;
        }

        .back-link:hover {
            color: var(--primary-gold);
        }
    </style>
</head>

<body class="login-page">
    <div class="grid-bg"></div>

    <div class="login-container animate__animated animate__zoomIn">
        <div class="glass-card-login">
            <div class="login-logo">
                <i class="fa-solid fa-university me-2 text-warning"></i>BFI <span class="highlight">FINANCE</span>
            </div>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger border-0 bg-danger bg-opacity-10 text-danger small mb-4 animate__animated animate__shakeX" role="alert" style="border-radius: 10px;">
                    <i class="fa-solid fa-circle-exclamation me-2"></i>
                    <?= $_SESSION['error']; ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <form action="modules/auth/proses-login.php" method="POST">
                <div class="mb-3">
                    <label class="form-label small">Username Admin</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-user-shield"></i>
                        </span>
                        <input type="text" name="username" class="form-control custom-input" placeholder="Username" required autocomplete="off">
                    </div>
                </div>

                <div class="mb-4">
                    <label class="form-label small">Password</label>
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="fa-solid fa-lock"></i>
                        </span>
                        <input type="password" name="password" class="form-control custom-input" placeholder="••••••••" required>
                    </div>
                </div>

                <button type="submit" name="submit" class="btn btn-login">
                    Masuk ke Dashboard
                </button>

                <a href="index.php" class="back-link">
                    <i class="fa-solid fa-arrow-left-long me-2"></i>Kembali ke Beranda
                </a>
            </form>
        </div>

        <p class="text-center mt-4 small text-white opacity-25">
            &copy; 2026 BFI Finance Digital System. All Rights Reserved.
        </p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>