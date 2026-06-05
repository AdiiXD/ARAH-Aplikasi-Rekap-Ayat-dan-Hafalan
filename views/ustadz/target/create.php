<?php
/** @var \App\Models\Santri $santri */
$title = "Tambah Target - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 500px;">
    <h3>Tambah Target Hafalan</h3>
    <form method="POST" action="index.php?action=ustadz/target/store&id=<?= $santri->id ?>">
        <div class="mb-3"><label>Target (contoh: Juz 30)</label><input type="text" name="target_ayat" class="form-control" required></div>
        <div class="mb-3"><label>Deadline</label><input type="date" name="deadline" class="form-control" required></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Simpan</button>
        <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>