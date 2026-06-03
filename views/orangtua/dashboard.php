<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $anakList */
/** @var \App\Models\QuranQuote|null $dailyQuote */
/** @var int $todayAyatCount */
/** @var \Illuminate\Database\Eloquent\Collection $todayDetails */
$title = "Dashboard Orang Tua";
$activeMenu = "dashboard";
ob_start();
?>

<!-- Statistik bacaan hari ini -->
<div class="card-custom p-3 mb-4">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <i class="bi bi-earbuds fs-2 text-maroon"></i>
        </div>
        <div class="text-end">
            <h3 class="mb-0"><?= $todayAyatCount ?> Ayat</h3>
            <small class="text-muted">Dibaca/Didengarkan Hari Ini</small>
        </div>
    </div>
    <?php if ($todayDetails && $todayDetails->isNotEmpty()): ?>
    <hr>
    <div class="small text-muted">
        <?php foreach ($todayDetails as $detail): ?>
        <div>📖 Surah <?= $detail->surah_number ?>: <?= $detail->total ?> ayat</div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Quote of the Day -->
<?php if ($dailyQuote): ?>
<div class="card-custom p-3 mb-4" style="background: linear-gradient(135deg, #4A1D2E 0%, #7A3F5A 100%); color: white;">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <small class="text-white-50"><i class="bi bi-quote"></i> Ayat Hari Ini</small>
            <div class="arabic-text mt-1" style="font-size: 1.2rem; direction: rtl;"><?= $dailyQuote->arabic_text ?></div>
            <div class="mt-1"><?= htmlspecialchars($dailyQuote->translation) ?></div>
            <small class="text-white-50">— <?= $dailyQuote->surah_name ?> : <?= $dailyQuote->ayat_number ?></small>
        </div>
        <div>
            <a href="index.php?action=quran/show&id=<?= $dailyQuote->surah_number ?>" class="btn btn-sm btn-outline-light rounded-pill px-3">📖 Baca Surat</a>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Selamat datang -->
<div class="card-custom p-4 mb-4">
    <h3>Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? 'Orang Tua') ?></h3>
    <p class="text-muted">Pantau hafalan anak Anda</p>
</div>

<!-- Daftar Anak -->
<div class="card-custom p-4">
    <h5 class="mb-3"><i class="bi bi-people"></i> Daftar Anak</h5>
    <?php if (empty($anakList) || $anakList->isEmpty()): ?>
        <div class="alert alert-info">Belum ada santri yang terhubung dengan akun Anda.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($anakList as $anak): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($anak->nama) ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-building"></i> Kelas: <?= htmlspecialchars($anak->kelas->nama_kelas ?? '-') ?><br>
                            <i class="bi bi-person-badge"></i> Ustadz: <?= htmlspecialchars($anak->ustadz->name ?? '-') ?>
                        </p>
                        <a href="index.php?action=orangtua/santri/show&id=<?= $anak->id ?>" class="btn btn-maroon btn-sm">
                            <i class="bi bi-graph-up"></i> Lihat Progress
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>