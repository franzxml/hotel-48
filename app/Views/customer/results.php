<!DOCTYPE html>
<html lang="id">
<head>
    <title>Hasil Pencarian</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">
    <div class="container">
        <h3>Kamar Tersedia: <?= $data['check_in']; ?> s/d <?= $data['check_out']; ?></h3>
        <a href="index.php?action=booking" class="btn btn-secondary mb-3">Ubah Tanggal</a>

        <div class="row">
            <?php if(empty($data['rooms'])): ?>
                <div class="alert alert-danger">Maaf, semua kamar penuh di tanggal ini :(</div>
            <?php else: ?>
                
                <?php foreach($data['rooms'] as $room): ?>
                <div class="col-md-4 mb-3">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title"><?= $room->type_name; ?> (Unit <?= $room->room_number; ?>)</h5>
                            <p class="text-muted"><?= $room->description; ?></p>
                            <h4 class="text-primary">Rp <?= number_format($room->price); ?> /malam</h4>
                            
                            <?php if (isset($_SESSION['user_id'])): ?>
                                
                                <form action="index.php?action=booking_process" method="POST">
                                    <input type="hidden" name="room_id" value="<?= $room->id; ?>">
                                    <input type="hidden" name="price" value="<?= $room->price; ?>">
                                    <input type="hidden" name="check_in" value="<?= $data['check_in']; ?>">
                                    <input type="hidden" name="check_out" value="<?= $data['check_out']; ?>">
                                    
                                    <button type="submit" class="btn btn-success w-100">Pesan Sekarang</button>
                                </form>

                            <?php else: ?>

                                <a href="index.php?action=login" class="btn btn-primary w-100">
                                    Login untuk Memesan
                                </a>

                            <?php endif; ?>
                            
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>

            <?php endif; ?>
        </div>
    </div>
</body>
</html>