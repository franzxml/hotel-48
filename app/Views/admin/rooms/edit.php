<!DOCTYPE html>
<html lang="id">
<head>
    <title><?= $data['title']; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container" style="max-width: 600px;">
        <div class="card shadow">
            <div class="card-header bg-warning text-dark">
                <h4>Edit Tipe Kamar</h4>
            </div>
            <div class="card-body">
                <form action="index.php?action=rooms_update" method="POST">
                    <input type="hidden" name="id" value="<?= $data['room']->id; ?>">

                    <div class="mb-3">
                        <label>Nama Tipe</label>
                        <input type="text" name="type_name" class="form-control" 
                               value="<?= $data['room']->type_name; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Harga per Malam</label>
                        <input type="number" name="price" class="form-control" 
                               value="<?= $data['room']->price; ?>" required>
                    </div>
                    <div class="mb-3">
                        <label>Deskripsi</label>
                        <textarea name="description" class="form-control" rows="3"><?= $data['room']->description; ?></textarea>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">Update Data</button>
                    <a href="index.php?action=rooms" class="btn btn-link w-100 text-center mt-2">Batal</a>
                </form>
            </div>
        </div>
    </div>
</body>
</html>