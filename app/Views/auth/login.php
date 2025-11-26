<!DOCTYPE html>
<html lang="id">
<head>
    <title>Login - Hotel 48</title>
    <style>
        body { font-family: sans-serif; display: flex; justify-content: center; padding-top: 50px; }
        .card { border: 1px solid #ccc; padding: 20px; border-radius: 8px; width: 300px; }
        input { width: 100%; margin-bottom: 10px; padding: 8px; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #007bff; color: white; border: none; cursor: pointer; border-radius: 4px;}
        .btn-google { background-color: #db4437; text-align: center; text-decoration: none; display: block; margin-top: 10px; color: white; padding: 10px; border-radius: 4px; }
    </style>
</head>
<body>

    <div class="card">
        <h2 style="text-align:center">Login Hotel 48</h2>
        
        <form action="index.php?action=login" method="POST">
            <label>Email</label>
            <input type="email" name="email" required placeholder="email@contoh.com">
            
            <label>Password</label>
            <input type="password" name="password" required placeholder="********">
            
            <button type="submit">Masuk</button>
        </form>
        
        <hr>
        <a href="index.php?action=login_google" class="btn-google">
            Login dengan Google
        </a>
        
        <p style="text-align:center; font-size:12px; margin-top:15px;">
            <a href="index.php?action=booking">Cari Kamar Tanpa Login</a>
        </p>
    </div>

</body>
</html>