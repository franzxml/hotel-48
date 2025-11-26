<!DOCTYPE html>
<html lang="id">
<head>
    <title>Pembayaran</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light p-5">
    <div class="card mx-auto shadow" style="max-width: 500px;">
        <div class="card-header bg-primary text-white text-center">
            <h4>Kasir Hotel 48</h4>
        </div>
        <div class="card-body text-center">
            <p>Total Tagihan untuk Booking #<?= $data['booking_id']; ?></p>
            <h2 class="fw-bold text-danger">Rp <?= number_format($data['amount']); ?></h2>
            <hr>
            <p class="mb-3">Pilih Metode Pembayaran:</p>
            
            <form action="index.php?action=payment_process" method="POST">
                <input type="hidden" name="booking_id" value="<?= $data['booking_id']; ?>">
                <input type="hidden" name="amount" value="<?= $data['amount']; ?>">
                
                <div class="d-grid gap-3">
                    <button type="submit" name="method" value="DANA" class="btn btn-outline-primary py-3 fw-bold">
                        🔵 Bayar via DANA
                    </button>
                    
                    <button type="submit" name="method" value="GOPAY" class="btn btn-outline-success py-3 fw-bold">
                        🟢 Bayar via GoPay
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>