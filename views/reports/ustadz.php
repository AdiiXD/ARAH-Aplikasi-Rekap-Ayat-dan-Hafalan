<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santriList */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Ustadz</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>/* sama seperti admin */</style>
</head>
<body>
<nav class="navbar navbar-maroon navbar-dark">...</nav>
<div class="container mt-4">
    <div class="card-custom p-4">
        <h3 class="mb-4">Export Laporan Hafalan</h3>
        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>PDF Rekap Hafalan Santri</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="action" value="ustadz/report/pdf">
                        <div class="mb-2">
                            <label>Pilih Santri</label>
                            <select name="santri_id" class="form-select" required>
                                <option value="">-- Pilih Santri --</option>
                                <?php foreach ($santriList as $s): ?>
                                <option value="<?= $s->id ?>"><?= htmlspecialchars($s->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-maroon"><i class="bi bi-file-pdf"></i> Export PDF</button>
                    </form>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="card p-3">
                    <h5>Export Excel Setoran Hafalan</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="action" value="ustadz/report/excel">
                        <div class="mb-2">
                            <label>Filter Kelas (Opsional)</label>
                            <select name="kelas_id" class="form-select">
                                <option value="">Semua Kelas</option>
                                <!-- Dinamis dari kelas santri binaan, bisa query langsung -->
                            </select>
                        </div>
                        <button type="submit" class="btn btn-maroon"><i class="bi bi-file-excel"></i> Export Excel</button>
                    </form>
                </div>
            </div>
        </div>
        <a href="index.php?action=ustadz/dashboard" class="btn btn-secondary">Kembali</a>
    </div>
</div>
</body>
</html>