<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BFI Finance - Simulasi & Pengajuan</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="../assets/index.css?v=1.4">
    <link rel="stylesheet" href="../assets/welcome.css">
</head>

<body class="landing-page">

    <div class="grid-bg"></div>
    <?php include 'includes/welcome/navbar-welcome.php'; ?>

    <main class="welcome-wrapper container mt-5 pt-5">
        <div class="row g-4 justify-content-center">

            <div class="col-lg-7 animate__animated animate__fadeInLeft">
                <div class="glass-card h-100 shadow-lg border border-white border-opacity-10">
                    <div class="d-flex align-items-center mb-4">
                        <div class="icon-box me-3 bg-warning text-dark rounded-3 d-flex align-items-center justify-content-center" style="width: 45px; height: 45px;">
                            <i class="fa-solid fa-calculator fs-5"></i>
                        </div>
                        <div>
                            <h2 class="h4 mb-0 fw-bold">Simulasi <span class="highlight text-warning">Dana Tunai</span></h2>
                            <p class="small text-white-50 mb-0">Hitung estimasi pencairan kendaraan Anda</p>
                        </div>
                    </div>

                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label small fw-bold opacity-75">Jenis Kendaraan</label>
                            <select id="jenisKendaraan" class="form-select custom-input bg-dark text-white border-white border-opacity-10 py-2">
                                <option value="">-- Pilih --</option>
                                <option value="1">Mobil</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold opacity-75">Merk</label>
                            <select id="merkKendaraan" class="form-select custom-input bg-dark text-white border-white border-opacity-10 py-2" disabled>
                                <option value="">-- Pilih Merk --</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label small fw-bold opacity-75">Tipe Spesifik</label>
                            <select id="pilihUnit" class="form-select custom-input bg-dark text-white border-white border-opacity-10 py-2" disabled>
                                <option value="0">-- Pilih Unit --</option>
                            </select>
                        </div>
                    </div>

                    <div class="result-area mt-4">
                        <div class="result-card-premium p-4 rounded-4" style="background: rgba(255, 255, 255, 0.05); border: 1px dashed rgba(255, 255, 255, 0.2);">
                            <div class="result-header d-flex justify-content-between align-items-center mb-3">
                                <span class="badge bg-warning text-dark fw-800 px-3">ESTIMASI HASIL</span>
                                <span class="small fw-bold text-warning">Pencairan Maksimal (95%)</span>
                            </div>

                            <div class="row g-4 align-items-center">
                                <div class="col-sm-6">
                                    <div class="p-3 rounded-4" style="background: rgba(15, 23, 42, 0.6); border: 1px solid rgba(255, 255, 255, 0.05);">
                                        <p class="small mb-1 text-uppercase fw-bold opacity-50" style="letter-spacing: 1px; font-size: 0.7rem;">Harga Pasar Unit</p>
                                        <div class="d-flex align-items-baseline gap-2">
                                            <span class="small opacity-50 fw-bold">Rp</span>
                                            <h3 id="hargaPasarHasil" class="mb-0 fw-800 text-white opacity-90">0</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-sm-6">
                                    <div class="col-12">
                                        <div class="p-3 rounded-4" style="background: rgba(255, 193, 7, 0.08); border: 1px solid rgba(255, 193, 7, 0.2);">
                                            <p class="small mb-1 text-uppercase fw-bold text-warning" style="letter-spacing: 1px; font-size: 0.7rem;">Estimasi Dana Cair</p>
                                            <div class="d-flex align-items-baseline gap-2">
                                                <span class="text-warning fw-bold">Rp</span>
                                                <h3 id="pinjamanHasil" class="mb-0 fw-800 text-warning">0</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="result-footer mt-4 pt-3 border-top border-white border-opacity-10">
                                <div class="d-flex align-items-start gap-2 text-white-50 mb-2">
                                    <i class="fa-solid fa-circle-info mt-1 text-warning" style="font-size: 0.8rem;"></i>
                                    <p class="small mb-0" style="line-height: 1.4;">
                                        Estimasi final ditentukan oleh kondisi fisik unit, kelengkapan dokumen, dan kebijakan mitra pembiayaan.
                                    </p>
                                </div>

                                <div class="d-flex align-items-center gap-2 py-2 px-3 rounded-3" style="background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.05);">
                                    <i class="fa-solid fa-calendar-check text-warning" style="font-size: 0.8rem;"></i>
                                    <p class="small mb-0 fw-bold text-white-50">
                                        Persyaratan Unit: <span class="text-white">Minimal tahun manufaktur 2007 ke atas.</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-5 animate__animated animate__fadeInRight">

                <div class="glass-card mb-4" id="syarat">
                    <div class="d-flex align-items-center mb-3">
                        <div class="icon-box-sm me-3"><i class="fa-solid fa-file-invoice"></i></div>
                        <h3 class="h5 fw-bold mb-0">Persyaratan <span class="highlight">Pengajuan</span></h3>
                    </div>

                    <div class="syarat-grid">
                        <div class="syarat-group mb-3">
                            <h6 class="text-warning small fw-bold text-uppercase mb-2">Kriteria Umum</h6>
                            <ul class="list-unstyled custom-list-sm">
                                <li><i class="fa-solid fa-check-circle"></i> WNI (Warga Negara Indonesia)</li>
                                <li><i class="fa-solid fa-check-circle"></i> Usia 21 - 50 Tahun</li>
                                <li><i class="fa-solid fa-check-circle"></i> Memiliki Penghasilan Tetap</li>
                                <li><i class="fa-solid fa-check-circle"></i> Domisili Area Layanan BFI</li>
                            </ul>
                        </div>

                        <div class="syarat-group">
                            <h6 class="text-warning small fw-bold text-uppercase mb-2">Dokumen Utama</h6>
                            <ul class="list-unstyled custom-list-sm">
                                <li><i class="fa-solid fa-id-card"></i> KTP Suami Istri & Kartu Keluarga</li>
                                <li><i class="fa-solid fa-file-signature"></i> STNK & BPKB Asli (Hanya diserahkan saat cair)</li>
                                <li><i class="fa-solid fa-money-check-dollar"></i> Slip Gaji / Bukti Rekening 3 Bulan</li>
                                <li><i class="fa-solid fa-house-user"></i> Bukti Kepemilikan Rumah (PBB/Listrik)</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="contact-cta glass-card text-center bg-gold-gradient shadow-hover">
                    <div class="cta-header mb-4">
                        <div class="cta-icon-glow">
                            <i class="fa-solid fa-headset"></i>
                        </div>
                        <h4 class="fw-bold mb-1">Konsultasi <span class="highlight">Gratis</span></h4>
                        <p class="small opacity-75">Proses cepat, transparan, & dipandu hingga cair.</p>
                    </div>

                    <div class="cta-actions">
                        <a href="https://wa.me/+6281228249959" class="btn-whatsapp mb-3">
                            <div class="btn-content">
                                <i class="fab fa-whatsapp"></i>
                                <span>Tanya via WhatsApp</span>
                            </div>
                            <i class="fa-solid fa-chevron-right small-arrow"></i>
                        </a>

                        <a href="tel:+628979638626" class="btn-phone">
                            <i class="fa-solid fa-phone-volume me-2"></i> Hubungi Sales Kami
                        </a>
                    </div>
                </div>
            </div>

        </div>
    </main>
    <?php include 'includes/welcome/footer-welcome.php'; ?>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/welcome.js"></script>

</html>