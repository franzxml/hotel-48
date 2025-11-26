<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title']; ?> - Hotel 48</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php?action=home">Hotel 48</a>
            
            <div class="d-flex align-items-center">
                <span class="navbar-text me-3 text-white">
                    Halo, <b><?= $data['user']; ?></b> (<?= strtoupper($data['role']); ?>)
                </span>
                <a href="index.php?action=logout" class="btn btn-danger btn-sm">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <h3 class="card-title">Dashboard Sistem</h3>
                <p class="card-text text-muted">Selamat datang kembali, Anda login sebagai <strong><?= $data['role']; ?></strong>.</p>
                <hr>

                <?php if ($data['role'] === 'admin'): ?>
                    
                    <div class="alert alert-info">
                        <i class="bi bi-gear-fill me-2"></i> Menu Administrator
                    </div>
                    
                    <div class="row g-4">
                        <div class="col-md-4">
                            <div class="card text-center h-100 border-primary">
                                <div class="card-body">
                                    <h1 class="text-primary"><i class="bi bi-tags"></i></h1>
                                    <h5 class="card-title mt-2">1. Tipe Kamar</h5>
                                    <p class="card-text small text-muted">Buat jenis kamar (Contoh: Deluxe, Standard) dan atur harganya.</p>
                                    <a href="index.php?action=rooms" class="btn btn-primary w-100">Kelola Tipe</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center h-100 border-success">
                                <div class="card-body">
                                    <h1 class="text-success"><i class="bi bi-door-open"></i></h1>
                                    <h5 class="card-title mt-2">2. Unit Fisik (Stok)</h5>
                                    <p class="card-text small text-muted">Daftarkan nomor kamar fisik (Contoh: 101, 102) agar stok tersedia.</p>
                                    <a href="index.php?action=units" class="btn btn-success w-100">Kelola Unit/Stok</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card text-center h-100 border-warning">
                                <div class="card-body">
                                    <h1 class="text-warning"><i class="bi bi-journal-text"></i></h1>
                                    <h5 class="card-title mt-2">3. Laporan Pesanan</h5>
                                    <p class="card-text small text-muted">Lihat daftar tamu yang booking dan status pembayarannya.</p>
                                    <a href="index.php?action=admin_bookings" class="btn btn-warning w-100">Lihat Data</a>                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="alert alert-success">
                        <i class="bi bi-emoji-smile me-2"></i> Menu Pelanggan
                    </div>

                    <div class="row justify-content-center g-4">
                        <div class="col-md-5">
                            <div class="card text-center h-100 shadow-sm hover-effect">
                                <div class="card-body p-4">
                                    <h1 class="text-success mb-3"><i class="bi bi-calendar-check"></i></h1>
                                    <h3>Pesan Kamar</h3>
                                    <p class="text-muted">Cari kamar kosong sesuai tanggal liburanmu.</p>
                                    <a href="index.php?action=booking" class="btn btn-success btn-lg px-5 w-100">Mulai Booking</a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="card text-center h-100 shadow-sm hover-effect">
                                <div class="card-body p-4">
                                    <h1 class="text-primary mb-3"><i class="bi bi-clock-history"></i></h1>
                                    <h3>Pesanan Saya</h3>
                                    <p class="text-muted">Cek status pembayaran dan riwayat menginap.</p>
                                    <a href="index.php?action=my_bookings" class="btn btn-primary btn-lg px-5 w-100">Lihat Riwayat</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php endif; ?>

            </div>
        </div>
    </div>

</body>
</html>