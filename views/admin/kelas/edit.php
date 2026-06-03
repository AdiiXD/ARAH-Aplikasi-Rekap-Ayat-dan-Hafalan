<?php
/** @var \App\Models\Kelas $kelas */
$title = "Edit Kelas";
$activeMenu = "kelas";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Edit Kelas</h3>
    <form method="POST" action="index.php?action=admin/kelas/update&id=<?= $kelas->id ?>">
        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" value="<?= htmlspecialchars($kelas->nama_kelas) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-control" rows="3"><?= htmlspecialchars($kelas->deskripsi) ?></textarea>
        </div>
        <button type="submit" class="btn btn-maroon">Update</button>
        <a href="index.php?action=admin/kelas" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>