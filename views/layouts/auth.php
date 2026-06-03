<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Hafalan Tracker' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { background: #FDF8F0; font-family: 'Inter', sans-serif; }
        .card-auth { background: #FFF9EF; border-radius: 2rem; box-shadow: 0 20px 35px -10px rgba(74,29,46,0.15); }
        .btn-maroon { background: #4A1D2E; color: white; border-radius: 50px; }
    </style>
</head>
<body class="d-flex align-items-center min-vh-100">
    <div class="container">
        <?= $content ?? '' ?>
    </div>
</body>
</html>