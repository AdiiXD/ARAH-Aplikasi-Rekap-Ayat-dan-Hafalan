<?php
$title = "Tambah Ustadz";
$activeMenu = "ustadz";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Tambah Ustadz Baru</h3>
    <form method="POST" action="index.php?action=admin/ustadz/store">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password</label>
            <input type="password" name="password" class="form-control" required>
            <small class="text-muted">Minimal 6 karakter</small>
        </div>
        <button type="submit" class="btn btn-maroon w-100">Simpan</button>
        <a href="index.php?action=admin/ustadz" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>