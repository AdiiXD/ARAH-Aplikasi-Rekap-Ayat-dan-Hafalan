<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $anakList */
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - Orang Tua</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>/* sama */</style>
</head>
<body>
<nav class="navbar navbar-maroon navbar-dark">...</nav>
<div class="container mt-4">
    <div class="card-custom p-4">
        <h3 class="mb-4">Cetak Laporan Progress Anak</h3>
        <div class="row">
            <div class="col-md-6">
                <div class="card p-3">
                    <h5>PDF Progress Hafalan</h5>
                    <form method="GET" action="index.php">
                        <input type="hidden" name="action" value="orangtua/report/pdf">
                        <div class="mb-2">
                            <label>Pilih Anak</label>
                            <select name="santri_id" class="form-select" required>
                                <option value="">-- Pilih --</option>
                                <?php foreach ($anakList as $anak): ?>
                                <option value="<?= $anak->id ?>"><?= htmlspecialchars($anak->nama) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-maroon"><i class="bi bi-file-pdf"></i> Download PDF</button>
                    </form>
                </div>
            </div>
        </div>
        <a href="index.php?action=orangtua/dashboard" class="btn btn-secondary mt-3">Kembali</a>
    </div>
</div>
</body>
</html>