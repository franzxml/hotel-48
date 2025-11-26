<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container" style="max-width: 500px;">
        <div class="card shadow">
            <div class="card-header bg-success text-white">
                <h4>Tambah Unit Kamar</h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=units_store" method="POST">
                    
                    <div class="mb-3">
                        <label>Pilih Tipe Kamar</label>
                        <select name="room_type_id" class="form-select" required>
                            <option value="">-- Pilih Tipe --</option>
                            <?php foreach ($data['types'] as $type): ?>
                                <option value="<?= $type->id; ?>">
                                    <?= $type->type_name; ?> (Rp <?= number_format($type->price); ?>)
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Nomor Kamar (Contoh: 101, 205)</label>
                        <input type="text" name="room_number" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-success w-100">Simpan Unit</button>
                    <a href="index.php?action=units" class="btn btn-link w-100 mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>