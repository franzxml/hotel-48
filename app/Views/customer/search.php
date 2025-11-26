<!DOCTYPE html>
<html lang="id">
<head>
    <title>Cari Kamar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4 bg-light">
    <div class="container text-center mt-5">
        <h1>Mau menginap kapan?</h1>
        <div class="card p-4 mt-4 shadow mx-auto" style="max-width: 600px;">
            <form action="index.php" method="GET">
                <input type="hidden" name="action" value="booking_search">
                
                <div class="row">
                    <div class="col-6">
                        <label>Check In</label>
                        <input type="date" name="check_in" class="form-control" required>
                    </div>
                    <div class="col-6">
                        <label>Check Out</label>
                        <input type="date" name="check_out" class="form-control" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Cari Ketersediaan</button>
                <a href="index.php?action=dashboard" class="btn btn-link mt-2">Kembali</a>
            </form>
        </div>
    </div>
</body>
</html>