<?php
/** @var \Illuminate\Database\Eloquent\Collection $bookmarks */
$title = "Bookmark Ayat";
$activeMenu = "bookmark";
?>
<div class="card-custom p-4">
    <h3><i class="bi bi-bookmark-star"></i> Bookmark Ayat</h3>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if ($bookmarks->isEmpty()): ?>
        <p class="text-muted">Belum ada bookmark. Simpan ayat favorit dari halaman pencarian atau saat membaca.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead><tr><th>Surat:Ayat</th><th>Nama Surat</th><th>Tanggal disimpan</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($bookmarks as $b): ?>
                <tr>
                    <td><?= $b->surah ?>:<?= $b->ayat ?></td>
                    <td><?= htmlspecialchars($b->surah_name ?: "Surat $b->surah") ?></td>
                    <td><?= $b->created_at->format('d M Y') ?></td>
                    <td>
                        <a href="index.php?action=quran/show&id=<?= $b->surah ?>#ayat-<?= $b->ayat ?>" class="btn btn-sm btn-maroon">Baca</a>
                        <a href="index.php?action=bookmark/remove&id=<?= $b->id ?>" class="btn btn-sm btn-danger" onclick="return confirm('Hapus bookmark?')">Hapus</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>