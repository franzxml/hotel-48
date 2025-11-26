<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-primary text-white">
                <h4>Tambah Tipe Kamar</h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=rooms_store" method="POST">
                    <div class="mb-3">
                        <label>Nama Tipe (Contoh: Deluxe)</label>
                        <input type="text" name="type_name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga per Malam</label>
                        <input type="number" name="price" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi & Fasilitas</label>
                        <textarea name="description" class="form-control" rows="3"></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-success w-100">Simpan Data</button>
                    <a href="index.php?action=rooms" class="btn btn-link w-100 text-center mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>