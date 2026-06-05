<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Kelas[] $kelasList */
$kelasList = $kelasList ?? collect();
$title = "Laporan - Admin | ARAH";
$activeMenu = "reports";
ob_start();
?>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-custom p-4 text-center h-100">
            <i class="bi bi-file-excel fs-1 text-maroon"></i>
            <h5 class="mt-3">Export Excel Santri</h5>
            <p class="text-muted">Download data santri berdasarkan kelas (opsional).</p>
            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="admin/report/excel">
                <div class="mb-3">
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php foreach ($kelasList as $k): ?>
                        <option value="<?= $k->id ?>"><?= htmlspecialchars($k->nama_kelas) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-maroon rounded-pill w-100"><i class="bi bi-download"></i> Export Excel</button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4 text-center h-100">
            <i class="bi bi-file-pdf fs-1 text-maroon"></i>
            <h5 class="mt-3">Export PDF Rekap Hafalan</h5>
            <p class="text-muted">Download laporan rekap per kelas (seluruh data).</p>
            <a href="index.php?action=admin/report/pdf" class="btn btn-maroon rounded-pill w-100"><i class="bi bi-download"></i> Export PDF</a>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>