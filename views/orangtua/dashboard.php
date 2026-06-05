<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $anakList */
/** @var \App\Models\QuranQuote|null $dailyQuote */
/** @var int $todayAyatCount */
/** @var \Illuminate\Database\Eloquent\Collection $todayDetails */

// Fallback default jika variabel tidak terdefinisi
$anakList = $anakList ?? collect();
$dailyQuote = $dailyQuote ?? null;
$todayAyatCount = $todayAyatCount ?? 0;
$todayDetails = $todayDetails ?? collect();

$title = "Dashboard ARAH";
$activeMenu = "dashboard";
ob_start();
?>

<!-- Hero Section dengan Quote of the Day -->
<?php if ($dailyQuote): ?>
<div class="card-custom p-4 mb-4" style="background: linear-gradient(135deg, #4A1D2E 0%, #7A3F5A 100%); color: white; border-radius: 32px;">
    <div class="row align-items-center">
        <div class="col-8">
            <div class="small text-white-50 mb-1"><i class="bi bi-quote"></i> Kutipan Hari Ini</div>
            <div class="arabic-text" style="font-size: 1.2rem; direction: rtl;"><?= $dailyQuote->arabic_text ?></div>
            <div class="mt-2"><?= htmlspecialchars($dailyQuote->translation) ?></div>
            <div class="mt-2">
                <a href="index.php?action=quran/show&id=<?= $dailyQuote->surah_number ?>" class="btn btn-sm btn-light rounded-pill px-3">📖 Baca Surat</a>
            </div>
        </div>
        <div class="col-4 text-end">
            <i class="bi bi-quote display-1 opacity-25"></i>
        </div>
    </div>
</div>
<?php endif; ?>

<!-- Statistik Aktivitas Mendengarkan -->
<div class="card-custom p-4 mb-4">
    <div class="row align-items-center">
        <div class="col-8">
            <div class="small text-muted mb-1"><i class="bi bi-earbuds"></i> Aktivitas Mendengarkan Hari Ini</div>
            <h2 class="mb-0"><?= $todayAyatCount ?> Ayat</h2>
            <small>Total ayat yang dibaca/didengarkan</small>
        </div>
        <div class="col-4 text-end">
            <i class="bi bi-mic display-1 text-maroon opacity-50"></i>
        </div>
    </div>
    <?php if ($todayDetails->isNotEmpty()): ?>
    <hr>
    <div class="d-flex flex-wrap gap-2 mt-2">
        <?php foreach ($todayDetails as $detail): ?>
        <span class="badge bg-light text-dark rounded-pill">📖 Surah <?= $detail->surah_number ?>: <?= $detail->total ?> ayat</span>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>
</div>

<!-- Daftar Anak -->
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h5 class="mb-0"><i class="bi bi-people"></i> Anak Saya</h5>
        <a href="index.php?action=orangtua/anak/create" class="btn btn-sm btn-outline-maroon rounded-pill">
            <i class="bi bi-person-plus"></i> Tambah Anak
        </a>
    </div>
    <?php if ($anakList->isEmpty()): ?>
        <div class="text-center py-5">
            <i class="bi bi-person-x fs-1 text-muted"></i>
            <p class="mt-2 mb-0">Belum ada santri yang terhubung.<br>Klik "Tambah Anak" untuk menghubungkan.</p>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($anakList as $anak): ?>
            <div class="col-md-6 col-lg-4">
                <div class="card h-100 border-0 shadow-sm rounded-4 hover-scale transition">
                    <div class="card-body">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-maroon rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 45px; height: 45px;">
                                <i class="bi bi-person text-white fs-5"></i>
                            </div>
                            <div>
                                <h6 class="card-title mb-0"><?= htmlspecialchars($anak->nama) ?></h6>
                                <small class="text-muted">NIS: <?= htmlspecialchars($anak->nis ?? '-') ?></small>
                            </div>
                        </div>
                        <p class="card-text small text-muted mb-2">
                            <i class="bi bi-person"></i> Panggilan: <?= htmlspecialchars($anak->nickname ?? '-') ?><br>
                            <i class="bi bi-building"></i> Kelas: <?= htmlspecialchars($anak->kelas->nama_kelas ?? '-') ?><br>
                            <i class="bi bi-person-badge"></i> Ustadz: <?= htmlspecialchars($anak->ustadz->name ?? '-') ?>
                        </p>
                        <a href="index.php?action=orangtua/santri/show&id=<?= $anak->id ?>" class="btn btn-maroon btn-sm w-100 rounded-pill">
                            Lihat Progress <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
    .hover-scale {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .hover-scale:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.1) !important;
    }
    .bg-maroon {
        background-color: #4A1D2E;
    }
    .text-maroon {
        color: #4A1D2E;
    }
    @media (max-width: 576px) {
        .card-custom {
            padding: 1rem;
        }
    }
</style>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>