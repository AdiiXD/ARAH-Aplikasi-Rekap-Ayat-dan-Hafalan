<?php
/** @var \App\Models\QuranQuote|null $dailyQuote */
$title = "Dashboard Admin";
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

<!-- Rest of dashboard content (statistik, dll) -->
<div class="card-custom p-4">
    <h3>Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></h3>
    <p class="text-muted">Dashboard administrator Hafalan Tracker</p>
    <hr>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-people fs-1 text-maroon"></i>
                <h4 id="totalSantri">0</h4>
                <span>Total Santri</span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-person-badge fs-1 text-maroon"></i>
                <h4 id="totalUstadz">0</h4>
                <span>Total Ustadz</span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-journal-bookmark-fill fs-1 text-maroon"></i>
                <h4 id="totalSetoranHariIni">0</h4>
                <span>Setoran Hari Ini</span>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>