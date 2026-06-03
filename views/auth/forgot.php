<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background: #FDF8F0; font-family: 'Inter', sans-serif; display: flex; align-items: center; min-height: 100vh; }
        .card-custom { background: #FFF9EF; border-radius: 32px; padding: 2rem; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
        .btn-maroon { background: #4A1D2E; color: white; border-radius: 50px; }
    </style>
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card-custom">
                <h3 class="text-center">Reset Password</h3>
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>
                <form method="POST" action="index.php?action=sendResetLink">
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-maroon w-100">Kirim Link Reset</button>
                </form>
                <div class="text-center mt-3"><a href="index.php?action=login">Kembali ke Login</a></div>
            </div>
        </div>
    </div>
</div>
</body>
</html>