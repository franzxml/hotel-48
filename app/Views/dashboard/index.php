<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $data['title']; ?> - Hotel 48</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Hotel 48</a>
            <div class="d-flex">
                <span class="navbar-text me-3">
                    Halo, <b><?= $data['user']; ?></b> (<?= strtoupper($data['role']); ?>)
                </span>
                <a href="index.php?action=logout" class="btn btn-outline-danger btn-sm">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <div class="card shadow-sm">
            <div class="card-body">
                <h3 class="card-title">Selamat Datang di Sistem Manajemen Hotel</h3>
                <p class="card-text">Anda login sebagai <strong><?= $data['role']; ?></strong>.</p>
                <hr>

                <?php if ($data['role'] === 'admin'): ?>
                    
                    <div class="alert alert-info">Menu Admin</div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card text-center mb-3">
                                <div class="card-body">
                                    <h5>Kelola Kamar</h5>
                                    <p>Tambah, Edit, Hapus Tipe Kamar</p>
                                    <a href="index.php?action=rooms" class="btn btn-primary">Buka Menu</a>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card text-center mb-3">
                                <div class="card-body">
                                    <h5>Cek Pesanan</h5>
                                    <p>Lihat reservasi masuk</p>
                                    <a href="#" class="btn btn-warning">Lihat Data</a>
                                </div>
                            </div>
                        </div>
                    </div>

                <?php else: ?>

                    <div class="alert alert-success">Menu Pelanggan</div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card text-center">
                                <div class="card-body">
                                    <h5>Pesan Kamar</h5>
                                    <p>Cari kamar kosong dan booking sekarang.</p>
                                    <a href="#" class="btn btn-success">Mulai Booking</a>
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