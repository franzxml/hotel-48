<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4 bg-light">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Riwayat Pesanan Saya</h2>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
        </div>

        <?php if(empty($data['bookings'])): ?>
            <div class="alert alert-warning">Anda belum pernah memesan kamar.</div>
        <?php else: ?>
            
            <div class="card shadow-sm">
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Kamar</th>
                                <th>Tanggal</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['bookings'] as $b): ?>
                            <tr>
                                <td>#<?= $b->id; ?></td>
                                <td>
                                    <strong><?= $b->type_name; ?></strong><br>
                                    <small class="text-muted">Unit <?= $b->room_number; ?></small>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">In: <?= $b->check_in; ?></span><br>
                                    <span class="badge bg-light text-dark border mt-1">Out: <?= $b->check_out; ?></span>
                                </td>
                                <td>Rp <?= number_format($b->total_price); ?></td>
                                <td>
                                    <?php if($b->status == 'confirmed'): ?>
                                        <span class="badge bg-success">LUNAS</span>
                                    <?php elseif($b->status == 'pending'): ?>
                                        <span class="badge bg-warning text-dark">BELUM BAYAR</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger"><?= strtoupper($b->status); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($b->status == 'confirmed'): ?>
                                        <a href="index.php?action=booking_invoice&id=<?= $b->id; ?>" class="btn btn-sm btn-outline-danger" target="_blank">
                                            <i class="bi bi-file-pdf"></i> Download PDF
                                        </a>
                                    <?php elseif($b->status == 'pending'): ?>
                                        <a href="index.php?action=payment&booking_id=<?= $b->id; ?>" class="btn btn-sm btn-primary">
                                            Bayar Sekarang
                                        </a>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        <?php endif; ?>
    </div>
</body>
</html>