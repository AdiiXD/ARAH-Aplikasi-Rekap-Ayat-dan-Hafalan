<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Kelas[] $kelasList */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body { background: #FDF8F0; font-family: 'Inter', sans-serif; }
        .navbar-maroon { background: #4A1D2E; }
        .card-custom { background: white; border-radius: 24px; border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .btn-maroon { background: #4A1D2E; color: white; border-radius: 50px; }
        .btn-maroon:hover { background: #7A3F5A; }
    </style>
</head>
<body>
<nav class="navbar navbar-maroon navbar-dark">
    <div class="container-fluid">
        <span class="navbar-brand"><i class="bi bi-file-earmark-text"></i> Laporan - Admin</span>
        <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">Logout</a>
    </div>
</nav>
<div class="container mt-4">
    <div class="card-custom p-4">
        <h3 class="mb-4">Export Data</h3>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>Export Excel Santri</h5>
                    <p>Download data santri berdasarkan kelas (opsional).</p>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="action" value="admin/report/excel">
                        <div class="mb-2">
                            <label>Pilih Kelas (Opsional)</label>
                            <select name="kelas_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                <?php foreach ($kelasList as $k): ?>
                                <option value="<?= $k->id ?>"><?= htmlspecialchars($k->nama_kelas) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-maroon"><i class="bi bi-file-excel"></i> Export Excel</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>Export PDF Rekap Hafalan</h5>
                    <p>Download laporan rekap per kelas (seluruh data).</p>
                    <a href="index.php?action=admin/report/pdf" class="btn btn-maroon"><i class="bi bi-file-pdf"></i> Export PDF</a>
                </div>
            </div>
        </div>
        <a href="index.php?action=admin/dashboard" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>
</div>
</body>
</html>