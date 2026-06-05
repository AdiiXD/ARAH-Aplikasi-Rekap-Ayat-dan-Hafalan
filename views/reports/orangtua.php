<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $anakList */
$anakList = $anakList ?? collect();
$title = "Laporan - Orang Tua | ARAH";
$activeMenu = "reports";
ob_start();
?>

<div class="row g-4">
    <div class="col-md-8 mx-auto">
        <div class="card-custom p-4 text-center">
            <i class="bi bi-file-pdf fs-1 text-maroon"></i>
            <h5 class="mt-3">PDF Progress Hafalan Anak</h5>
            <p class="text-muted">Pilih anak untuk mencetak laporan progress hafalan.</p>
            <form method="GET" action="index.php" class="mt-3">
                <input type="hidden" name="action" value="orangtua/report/pdf">
                <div class="mb-3">
                    <select name="santri_id" class="form-select" required>
                        <option value="">-- Pilih Anak --</option>
                        <?php foreach ($anakList as $anak): ?>
                        <option value="<?= $anak->id ?>"><?= htmlspecialchars($anak->nama) ?> (<?= htmlspecialchars($anak->nis ?? '-') ?>)</option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-maroon rounded-pill w-100"><i class="bi bi-download"></i> Export PDF</button>
            </form>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>