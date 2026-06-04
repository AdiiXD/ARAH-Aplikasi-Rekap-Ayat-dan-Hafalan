<?php
$title = "Registrasi Orang Tua";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Orang Tua - Hafalan Tracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            background: #FDF8F0;
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .register-card {
            background: #FFF9EF;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -10px rgba(74,29,46,0.15);
            padding: 2rem 1.8rem;
            max-width: 550px;
            width: 100%;
        }
        .btn-maroon {
            background: #4A1D2E;
            color: white;
            border-radius: 50px;
            padding: 0.65rem;
        }
        .btn-maroon:hover {
            background: #7A3F5A;
        }
        .form-control {
            border-radius: 16px;
            border: 1px solid #E6DDD0;
            padding: 0.65rem 1rem;
        }
        label {
            font-weight: 600;
            color: #4A1D2E;
            margin-bottom: 0.4rem;
        }
    </style>
</head>
<body>
<div class="register-card">
    <div class="text-center mb-4">
        <i class="bi bi-person-plus fs-1" style="color: #4A1D2E;"></i>
        <h3>Registrasi Orang Tua</h3>
        <p class="text-muted">Daftar untuk memantau hafalan anak Anda</p>
    </div>

    <?php if (isset($_SESSION['errors'])): ?>
        <div class="alert alert-danger">
            <ul class="mb-0">
                <?php foreach ($_SESSION['errors'] as $err): ?>
                    <li><?= htmlspecialchars($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
        <?php unset($_SESSION['errors']); ?>
    <?php endif; ?>

    <form method="POST" action="index.php?action=register">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required autofocus>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-muted">Minimal 6 karakter</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Konfirmasi Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <hr>
        <h6 class="mb-3">Data Anak (Santri)</h6>
        <div class="mb-3">
            <label class="form-label">NIS Santri</label>
            <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Santri" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Nickname Santri</label>
            <input type="text" name="nickname" class="form-control" placeholder="Nama panggilan santri" required>
        </div>
        <button type="submit" class="btn btn-maroon w-100">Daftar Sekarang</button>
        <div class="text-center mt-3">
            Sudah punya akun? <a href="index.php?action=login">Login</a>
        </div>
    </form>
</div>
</body>
</html>