<?php
// Jika ada parameter 'goto' dengan format "surah:ayat", redirect ke halaman surat
if (isset($_GET['goto']) && preg_match('/^(\d+):(\d+)$/', $_GET['goto'], $matches)) {
    $surahId = $matches[1];
    $ayat = $matches[2];
    header("Location: index.php?action=quran/show&id=$surahId#ayat-$ayat");
    exit;
}
?>
<div class="card-custom p-4">
    <h3><i class="bi bi-book"></i> Daftar Surat Al-Quran</h3>
    <p class="text-muted">114 Surat, dari Al-Fatihah hingga An-Nas</p>

    <!-- Form pencarian dan filter -->
    <form method="GET" action="index.php" class="row g-3 mb-4">
        <input type="hidden" name="action" value="quran">
        <div class="col-md-5">
            <label class="form-label">Cari Surat</label>
            <input type="text" name="search" class="form-control" placeholder="Nama surat atau nomor surat" value="<?= htmlspecialchars($searchQuery ?? '') ?>">
        </div>
        <div class="col-md-3">
            <label class="form-label">Tempat Turun</label>
            <select name="revelation" class="form-select">
                <option value="">Semua</option>
                <option value="makkah" <?= ($revelationFilter ?? '') == 'makkah' ? 'selected' : '' ?>>Makkah</option>
                <option value="madinah" <?= ($revelationFilter ?? '') == 'madinah' ? 'selected' : '' ?>>Madinah</option>
            </select>
        </div>
        <div class="col-md-2 align-self-end">
            <button type="submit" class="btn btn-maroon w-100">Filter</button>
        </div>
        <div class="col-md-2 align-self-end">
            <a href="index.php?action=quran" class="btn btn-secondary w-100">Reset</a>
        </div>
    </form>

    <!-- Form navigasi langsung ke surah:ayat -->
    <div class="bg-light p-3 rounded mb-4">
        <form method="GET" action="index.php" class="row g-2 align-items-end">
            <input type="hidden" name="action" value="quran">
            <div class="col-md-4">
                <label class="form-label">Langsung ke Surah:Ayat</label>
                <input type="text" name="goto" class="form-control" placeholder="2:255">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-success">Buka</button>
            </div>
        </form>
    </div>

    <?php if (empty($chapters)): ?>
        <div class="alert alert-warning">Tidak ada surat yang ditemukan.</div>
    <?php else: ?>
        <div class="row">
            <?php foreach ($chapters as $chapter): ?>
            <div class="col-md-4 col-lg-3 mb-3">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title"><?= $chapter['id'] ?>. <?= htmlspecialchars($chapter['name_simple'] ?? '') ?></h5>
                        <p class="card-text text-muted">
                            <i class="bi bi-translate"></i> <?= htmlspecialchars($chapter['name_arabic'] ?? '') ?>
                        </p>
                        <p class="card-text">
                            <small>Jumlah Ayat: <strong><?= $chapter['verses_count'] ?? 0 ?></strong></small><br>
                            <small>Tempat Turun: <strong><?= ($chapter['revelation_place'] ?? '') == 'makkah' ? 'Makkah' : 'Madinah' ?></strong></small>
                        </p>
                        <a href="index.php?action=quran/show&id=<?= $chapter['id'] ?? 0 ?>" class="btn btn-sm btn-maroon">Baca Surat</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>