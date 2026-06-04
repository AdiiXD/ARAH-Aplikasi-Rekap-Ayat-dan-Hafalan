<?php
// Tampilkan pesan error/success jika ada dari session
$error = $_SESSION['error'] ?? null;
$success = $_SESSION['success'] ?? null;
unset($_SESSION['error'], $_SESSION['success']);
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title>Login - Hafalan Tracker</title>
    <!-- Bootstrap 5 + Icons + Font Inter -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,500;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #FDF8F0;
            /* cream background */
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }

        .login-card {
            background: #FFF9EF;
            border-radius: 2rem;
            box-shadow: 0 20px 35px -10px rgba(74, 29, 46, 0.15);
            padding: 2rem 1.8rem;
            max-width: 450px;
            width: 100%;
            transition: all 0.2s ease;
            border: 1px solid rgba(74, 29, 46, 0.08);
        }

        .login-header {
            text-align: center;
            margin-bottom: 2rem;
        }

        .login-header .logo-icon {
            background: #4A1D2E;
            width: 60px;
            height: 60px;
            line-height: 60px;
            text-align: center;
            border-radius: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
        }

        .login-header .logo-icon i {
            font-size: 2rem;
            color: #FDF8F0;
        }

        .login-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: #2C2C2C;
            margin-bottom: 0.25rem;
        }

        .login-header p {
            color: #6B6B6B;
            font-size: 0.9rem;
        }

        .form-label {
            font-weight: 600;
            color: #4A1D2E;
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }

        .input-group-custom {
            border-radius: 60px;
            border: 1px solid #E6DDD0;
            background: white;
            transition: all 0.2s;
        }

        .input-group-custom:focus-within {
            border-color: #4A1D2E;
            box-shadow: 0 0 0 3px rgba(74, 29, 46, 0.1);
        }

        .form-control-custom {
            border: none;
            background: transparent;
            padding: 0.75rem 1rem;
            font-size: 0.95rem;
            border-radius: 60px;
            outline: none;
            width: 100%;
        }

        .form-control-custom:focus {
            box-shadow: none;
            background: transparent;
        }

        .btn-maroon {
            background: #4A1D2E;
            color: white;
            border: none;
            border-radius: 60px;
            padding: 0.75rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.2s;
            width: 100%;
        }

        .btn-maroon:hover {
            background: #7A3F5A;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(74, 29, 46, 0.25);
        }

        .btn-maroon:active {
            transform: translateY(0);
        }

        .forgot-link {
            color: #7A3F5A;
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
        }

        .forgot-link:hover {
            color: #4A1D2E;
            text-decoration: underline;
        }

        .alert-custom {
            border-radius: 50px;
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
            margin-bottom: 1.2rem;
            border: none;
        }

        .alert-danger-custom {
            background: #FFE5E5;
            color: #B13E3E;
        }

        .alert-success-custom {
            background: #E0F2E6;
            color: #1F6E43;
        }

        hr {
            background-color: #E6DDD0;
            margin: 1.5rem 0;
        }

        /* Mobile first sudah default, tambahan sentuhan */
        @media (max-width: 480px) {
            .login-card {
                padding: 1.5rem;
            }

            .login-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>

<body>

    <div class="login-card">
        <div class="login-header">
            <div class="logo-icon">
                <i class="bi bi-book-half"></i>
            </div>
            <h1>Hafalan Tracker</h1>
            <p>Masuk ke dashboard Anda</p>
        </div>

        <?php if ($error): ?>
            <div class="alert alert-custom alert-danger-custom">
                <i class="bi bi-exclamation-triangle-fill me-2"></i> <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <?php if ($success): ?>
            <div class="alert alert-custom alert-success-custom">
                <i class="bi bi-check-circle-fill me-2"></i> <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="index.php?action=login">
            <div class="mb-3">
                <label class="form-label">Alamat Email</label>
                <div class="input-group-custom d-flex align-items-center">
                    <span style="padding-left: 1rem; color:#6B6B6B;"><i class="bi bi-envelope"></i></span>
                    <input type="email" name="email" class="form-control-custom" placeholder="admin@hafalan.com" required autofocus>
                </div>
            </div>

            <div class="mb-4">
                <label class="form-label">Kata Sandi</label>
                <div class="input-group-custom d-flex align-items-center">
                    <span style="padding-left: 1rem; color:#6B6B6B;"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control-custom" placeholder="********" required>
                </div>
            </div>

            <button type="submit" class="btn btn-maroon">
                <i class="bi bi-box-arrow-in-right me-2"></i> Masuk
            </button>

            <hr>
            <div class="text-center mt-3">
                Belum punya akun? <a href="index.php?action=register">Daftar sebagai Orang Tua</a>
            </div>

            <div class="text-center">
                <a href="index.php?action=forgot" class="forgot-link">
                    <i class="bi bi-question-circle"></i> Lupa password?
                </a>
            </div>
        </form>

        <!-- <div class="mt-3 text-center text-muted small">
        <span>Demo: admin@hafalan.com / password</span><br>
        <span>ustadz@hafalan.com / password | orangtua@example.com / password</span>
    </div> -->
    </div>

</body>

</html>