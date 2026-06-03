<?php
$title = "Pencarian Ayat";
$activeMenu = "quran_search";
$query = $_GET['q'] ?? '';
$results = $results ?? [];
?>
<div class="card-custom p-4">
    <h3><i class="bi bi-search"></i> Pencarian Ayat</h3>
    <form method="GET" action="index.php" class="mt-3">
        <input type="hidden" name="action" value="quran/search">
        <div class="input-group">
            <input type="text" name="q" class="form-control" placeholder="Cari kata dalam Al-Qur'an..." value="<?= htmlspecialchars($query) ?>" required>
            <button class="btn btn-maroon" type="submit">Cari</button>
        </div>
    </form>

    <?php if ($query && empty($results)): ?>
        <div class="alert alert-warning mt-4">Tidak ditemukan ayat untuk kata "<?= htmlspecialchars($query) ?>".</div>
    <?php elseif ($results): ?>
        <div class="mt-4">
            <p>Ditemukan <?= count($results) ?> hasil.</p>
            <?php foreach ($results as $verse): ?>
            <?php
                $surah = $verse['verse_key'] ?? '';
                $surahId = explode(':', $surah)[0] ?? '';
                $ayat = $verse['verse_number'] ?? '';
                $text = $verse['text_uthmani'] ?? '';
                $translation = $verse['translations'][0]['text'] ?? '';
            ?>
            <div class="border rounded p-3 mb-3">
                <div class="fw-bold">QS. <?= $verse['verse_key'] ?></div>
                <div class="arabic-text" style="font-size:1.2rem; direction:rtl"><?= $text ?></div>
                <div class="text-muted"><?= strip_tags($translation) ?></div>
                <a href="index.php?action=quran/show&id=<?= $surahId ?>#ayat-<?= $ayat ?>" class="btn btn-sm btn-outline-maroon mt-2">Baca Surat</a>
                <form method="POST" action="index.php?action=bookmark/add" class="d-inline">
                    <input type="hidden" name="surah" value="<?= $surahId ?>">
                    <input type="hidden" name="ayat" value="<?= $ayat ?>">
                    <input type="hidden" name="surah_name" value="<?= htmlspecialchars($verse['verse_key'] ?? '') ?>">
                    <button type="submit" class="btn btn-sm btn-outline-info mt-2">🔖 Bookmark</button>
                </form>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>