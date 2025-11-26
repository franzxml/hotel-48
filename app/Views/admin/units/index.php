<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Fisik Kamar (Unit)</h2>
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary">Dashboard</a>
                <a href="index.php?action=units_create" class="btn btn-primary">+ Tambah Unit</a>
            </div>
        </div>

        <table class="table table-bordered text-center">
            <thead class="table-dark">
                <tr>
                    <th>Nomor Kamar</th>
                    <th>Tipe</th>
                    <th>Status Saat Ini</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($data['units'] as $unit): ?>
                <tr>
                    <td class="fw-bold"><?= $unit->room_number; ?></td>
                    <td><?= $unit->type_name; ?></td> <td>
                        <span class="badge bg-success"><?= $unit->status; ?></span>
                    </td>
                    <td>
                        <a href="index.php?action=units_delete&id=<?= $unit->id; ?>" 
                           class="btn btn-sm btn-danger"
                           onclick="return confirm('Hapus unit ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>