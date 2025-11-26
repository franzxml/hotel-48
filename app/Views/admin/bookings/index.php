<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Laporan Semua Pesanan</h2>
            <a href="index.php?action=dashboard" class="btn btn-secondary">Kembali Dashboard</a>
        </div>

        <div class="card shadow">
            <div class="card-body">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Nama Tamu</th>
                            <th>Kamar</th>
                            <th>Tanggal Check-in/Out</th>
                            <th>Total Harga</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($data['bookings'] as $b): ?>
                        <tr>
                            <td>#<?= $b->id; ?></td>
                            <td><?= $b->guest_name; ?></td>
                            <td>
                                <b><?= $b->room_number; ?></b> 
                                <span class="text-muted">(<?= $b->type_name; ?>)</span>
                            </td>
                            <td>
                                <?= $b->check_in; ?> <br> s/d <br> <?= $b->check_out; ?>
                            </td>
                            <td>Rp <?= number_format($b->total_price); ?></td>
                            <td>
                                <?php if($b->status == 'confirmed'): ?>
                                    <span class="badge bg-success">Confirmed</span>
                                <?php elseif($b->status == 'pending'): ?>
                                    <span class="badge bg-warning text-dark">Pending</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary"><?= $b->status; ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>