<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santriList */
/** @var \App\Models\QuranQuote|null $dailyQuote */
$title = "Dashboard Ustadz";
$activeMenu = "dashboard";
ob_start();
?>

<!-- Quote Card Minimalis -->
<?php if ($dailyQuote): ?>
<div class="card-custom p-4 mb-4" style="background: linear-gradient(120deg, #4A1D2E 0%, #6B3A4F 100%); color: white; border: none; border-radius: 24px;">
    <div class="row align-items-center">
        <div class="col-12 col-md-9">
            <small class="text-white-50 text-uppercase tracking-wide"><i class="bi bi-quote"></i> Ayat Hari Ini</small>
            <div class="arabic-text mt-2" style="font-size: 1.4rem; line-height: 2rem; direction: rtl;"><?= $dailyQuote->arabic_text ?></div>
            <div class="mt-2 fst-italic"><?= htmlspecialchars($dailyQuote->translation) ?></div>
            <div class="mt-1"><small class="text-white-50">— <?= $dailyQuote->surah_name ?> : <?= $dailyQuote->ayat_number ?></small></div>
        </div>
        <div class="col-12 col-md-3 text-md-end mt-3 mt-md-0">
            <a href="index.php?action=quran/show&id=<?= $dailyQuote->surah_number ?>" class="btn btn-outline-light btn-sm rounded-pill px-3" style="border-width: 1px;">
                <i class="bi bi-book-open"></i> Baca Surat
            </a>
        </div>
    </div>
</div>
<?php endif; ?>

<div class="card-custom p-4 mb-4">
    <h3>Selamat datang, Ustadz <?= htmlspecialchars($_SESSION['name'] ?? 'Ustadz') ?></h3>
    <p class="text-muted">Kelola hafalan santri binaan Anda</p>
</div>

<div class="card-custom p-4">
    <h5 class="mb-3"><i class="bi bi-people"></i> Daftar Santri Binaan</h5>
    <?php if (empty($santriList) || $santriList->isEmpty()): ?>
        <div class="alert alert-info">Belum ada santri yang dibimbing.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($santriList as $santri): ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($santri->nama) ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-building"></i> Kelas: <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?><br>
                            <i class="bi bi-calendar"></i> Tahun Masuk: <?= $santri->tahun_masuk ?>
                        </p>
                        <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-maroon btn-sm">
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