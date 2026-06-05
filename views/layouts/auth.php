<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $title ?? 'ARAH - Aplikasi Rekap Ayat dan Hafalan' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon-dark: #4A1D2E;
            --maroon-light: #7A3F5A;
            --cream-bg: #FDF8F0;
            --cream-card: #FFF9EF;
            --text-dark: #1E1E1E;
            --text-muted: #6B6B6B;
            --border-light: #E6DDD0;
            --shadow-md: 0 8px 30px rgba(0,0,0,0.1);
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: linear-gradient(135deg, #FDF8F0 0%, #FFF9EF 100%);
            font-family: 'Inter', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
        }
        .auth-card {
            background: var(--cream-card);
            border-radius: 2rem;
            box-shadow: var(--shadow-md);
            padding: 2rem 1.8rem;
            max-width: 450px;
            width: 100%;
            transition: transform 0.2s;
        }
        .auth-card:hover {
            transform: translateY(-5px);
        }
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        .auth-header .logo {
            background: var(--maroon-dark);
            width: 70px;
            height: 70px;
            line-height: 70px;
            text-align: center;
            border-radius: 35px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1rem;
            box-shadow: 0 8px 16px rgba(74,29,46,0.2);
        }
        .auth-header .logo i {
            font-size: 2.2rem;
            color: white;
        }
        .auth-header h1 {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--text-dark);
            margin-bottom: 0.25rem;
        }
        .auth-header p {
            color: var(--text-muted);
            font-size: 0.9rem;
        }
        .form-label {
            font-weight: 600;
            color: var(--maroon-dark);
            font-size: 0.85rem;
            margin-bottom: 0.4rem;
        }
        .form-control {
            border-radius: 60px;
            border: 1px solid var(--border-light);
            background: white;
            padding: 0.75rem 1rem;
            transition: all 0.2s;
        }
        .form-control:focus {
            border-color: var(--maroon-dark);
            box-shadow: 0 0 0 3px rgba(74,29,46,0.1);
            outline: none;
        }
        .btn-maroon {
            background: var(--maroon-dark);
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
            background: var(--maroon-light);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(74,29,46,0.25);
        }
        .btn-maroon:active {
            transform: translateY(0);
        }
        .alert-custom {
            border-radius: 50px;
            font-size: 0.85rem;
            padding: 0.6rem 1rem;
            margin-bottom: 1.2rem;
        }
        hr {
            background-color: var(--border-light);
            margin: 1.5rem 0;
        }
        @media (max-width: 480px) {
            .auth-card {
                padding: 1.5rem;
            }
            .auth-header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>

<div class="auth-card">
    <div class="auth-header">
        <div class="logo">
            <i class="bi bi-book-half"></i>
        </div>
        <h1>ARAH</h1>
        <p>Aplikasi Rekap Ayat dan Hafalan</p>
    </div>

    <?= $content ?? '' ?>
</div>

</body>
</html>