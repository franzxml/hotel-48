<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Daftar Tipe Kamar</h2>
            <div>
                <a href="index.php?action=dashboard" class="btn btn-secondary">Kembali Dashboard</a>
                <a href="index.php?action=rooms_create" class="btn btn-primary">+ Tambah Baru</a>
            </div>
        </div>

        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nama Tipe</th>
                    <th>Deskripsi</th>
                    <th>Harga</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php $no = 1; foreach ($data['rooms'] as $room): ?>
                <tr>
                    <td><?= $no++; ?></td>
                    <td><?= $room->type_name; ?></td>
                    <td><?= $room->description; ?></td>
                    <td>Rp <?= number_format($room->price, 0, ',', '.'); ?></td>
                    <td>
                        <a href="index.php?action=rooms_edit&id=<?= $room->id; ?>" class="btn btn-sm btn-warning">Edit</a>
                        
                        <a href="index.php?action=rooms_delete&id=<?= $room->id; ?>" 
                        class="btn btn-sm btn-danger" 
                        onclick="return confirm('Yakin mau hapus data ini?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>