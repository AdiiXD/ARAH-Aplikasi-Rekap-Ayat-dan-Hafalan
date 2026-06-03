<?php
$title = "Tambah Kelas";
$activeMenu = "kelas";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Tambah Kelas Baru</h3>
    <form method="POST" action="index.php?action=admin/kelas/store">
        <div class="mb-3">
            <label class="form-label">Nama Kelas</label>
            <input type="text" name="nama_kelas" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Deskripsi (Opsional)</label>
            <textarea name="deskripsi" class="form-control" rows="3"></textarea>
        </div>
        <button type="submit" class="btn btn-maroon">Simpan</button>
        <a href="index.php?action=admin/kelas" class="btn btn-secondary">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>