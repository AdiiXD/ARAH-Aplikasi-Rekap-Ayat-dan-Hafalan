<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santriList */
$santriList = $santriList ?? collect();
$title = "Laporan - Ustadz | ARAH";
$activeMenu = "reports";
ob_start();
?>

<div class="row g-4">
    <div class="col-md-6">
        <div class="card-custom p-4 text-center h-100">
            <i class="bi bi-file-pdf fs-1 text-maroon"></i>
            <h5 class="mt-3">PDF Rekap Hafalan Santri</h5>
            <p class="text-muted">Pilih santri untuk mencetak laporan hafalan individual.</p>
            <form method="GET" action="index.php" class="mt-3">
                <input type="hidden" name="action" value="ustadz/report/pdf">
                <div class="mb-3">
                    <select name="santri_id" class="form-select" required>
                        <option value="">-- Pilih Santri --</option>
                        <?php foreach ($santriList as $s): ?>
                        <option value="<?= $s->id ?>"><?= htmlspecialchars($s->nama) ?> (<?= htmlspecialchars($s->nis ?? '-') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-maroon rounded-pill w-100"><i class="bi bi-download"></i> Export PDF</button>
            </form>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4 text-center h-100">
            <i class="bi bi-file-excel fs-1 text-maroon"></i>
            <h5 class="mt-3">Export Excel Setoran Hafalan</h5>
            <p class="text-muted">Download data setoran berdasarkan kelas (opsional).</p>
            <form method="GET" action="index.php">
                <input type="hidden" name="action" value="ustadz/report/excel">
                <div class="mb-3">
                    <select name="kelas_id" class="form-select">
                        <option value="">Semua Kelas</option>
                        <?php
                        // Asumsikan ada daftar kelas dari santri binaan (bisa dikirim dari controller)
                        $kelasList = $kelasList ?? collect();
                        foreach ($kelasList as $k):
                        ?>
                        <option value="<?= $k->id ?>"><?= htmlspecialchars($k->nama_kelas) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-maroon rounded-pill w-100"><i class="bi bi-download"></i> Export Excel</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>