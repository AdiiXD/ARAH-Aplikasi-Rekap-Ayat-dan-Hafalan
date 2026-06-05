<?php
$title = "Tambah Kelas - ARAH";
$activeMenu = "kelas";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 500px;">
    <h3><i class="bi bi-plus-circle"></i> Tambah Kelas</h3>
    <form method="POST" action="index.php?action=admin/kelas/store">
        <div class="mb-3"><label>Nama Kelas</label><input type="text" name="nama_kelas" class="form-control" required></div>
        <div class="mb-3"><label>Deskripsi (opsional)</label><textarea name="deskripsi" class="form-control" rows="2"></textarea></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Simpan</button>
        <a href="index.php?action=admin/kelas" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>