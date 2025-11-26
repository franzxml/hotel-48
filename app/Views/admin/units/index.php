<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Manajemen Aset Kamar</h2>
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary">Dashboard</a>
                <a href="index.php?action=units_create" class="btn btn-primary">+ Tambah Unit</a>
            </div>
        </div>

        <table class="table table-bordered text-center align-middle">
            <thead class="table-dark">
                <tr>
                    <th>No. Kamar</th>
                    <th>Tipe</th>
                    <th>Kondisi Fisik</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['units'] as $unit): ?>
                <tr>
                    <td class="fw-bold fs-5"><?= $unit->room_number; ?></td>
                    <td><?= $unit->type_name; ?></td>
                    <td>
                        <?php if ($unit->status == 'available'): ?>
                            <span class="badge bg-success px-3 py-2">
                                <i class="bi bi-check-circle"></i> BAIK / SIAP PAKAI
                            </span>
                        <?php else: ?>
                            <span class="badge bg-danger px-3 py-2">
                                <i class="bi bi-exclamation-triangle"></i> RUSAK / MAINTENANCE
                            </span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($unit->status == 'available'): ?>
                            <a href="index.php?action=units_toggle&id=<?= $unit->id; ?>" 
                               class="btn btn-sm btn-outline-warning"
                               onclick="return confirm('Tandai kamar ini sedang RUSAK/PERBAIKAN?')">
                               <i class="bi bi-wrench"></i> Maintenance
                            </a>
                        <?php else: ?>
                            <a href="index.php?action=units_toggle&id=<?= $unit->id; ?>" 
                               class="btn btn-sm btn-outline-success"
                               onclick="return confirm('Kamar sudah selesai diperbaiki?')">
                               <i class="bi bi-check-lg"></i> Selesai Perbaikan
                            </a>
                        <?php endif; ?>

                        <a href="index.php?action=units_delete&id=<?= $unit->id; ?>" 
                           class="btn btn-sm btn-danger ms-2"
                           onclick="return confirm('Hapus unit ini permanen?')">
                           <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>