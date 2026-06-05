<?php
$title = "Tambah Anak";
$activeMenu = "tambah_anak";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 500px;">
    <h3 class="mb-4"><i class="bi bi-person-plus"></i> Hubungkan Anak</h3>
    <p class="text-muted">Masukkan NIS dan nickname santri yang telah didaftarkan oleh admin.</p>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <form method="POST" action="index.php?action=orangtua/anak/store">
        <div class="mb-3">
            <label class="form-label">NIS Santri <span class="text-danger">*</span></label>
            <input type="text" name="nis" class="form-control" placeholder="Contoh: 12345" required>
            <small class="text-muted">Nomor Induk Santri dari data admin.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Nickname Santri <span class="text-danger">*</span></label>
            <input type="text" name="nickname" class="form-control" placeholder="Nama panggilan santri" required>
        </div>
        <button type="submit" class="btn btn-maroon w-100">Hubungkan</button>
        <a href="index.php?action=orangtua/dashboard" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>