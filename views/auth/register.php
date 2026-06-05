<?php
$title = "Registrasi - ARAH";
ob_start();
?>

<?php if (isset($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-custom">
        <ul class="mb-0 ps-3">
            <?php foreach ($_SESSION['errors'] as $err): ?>
                <li><?= htmlspecialchars($err) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
    <?php unset($_SESSION['errors']); ?>
<?php endif; ?>

<form method="POST" action="index.php?action=register">
    <div class="mb-3">
        <label class="form-label">Nama Lengkap</label>
        <input type="text" name="name" class="form-control" placeholder="Nama lengkap" required autofocus>
    </div>
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="Minimal 6 karakter" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Konfirmasi Password</label>
        <input type="password" name="confirm_password" class="form-control" placeholder="Ulangi password" required>
    </div>
    <hr>
    <h6 class="mb-3">Data Anak (Santri)</h6>
    <div class="mb-3">
        <label class="form-label">NIS Santri</label>
        <input type="text" name="nis" class="form-control" placeholder="Nomor Induk Santri" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Nickname Santri</label>
        <input type="text" name="nickname" class="form-control" placeholder="Nama panggilan santri" required>
    </div>
    <button type="submit" class="btn btn-maroon">Daftar Sekarang</button>
    <hr>
    <div class="text-center">
        <span class="small text-muted">Sudah punya akun? <a href="index.php?action=login" class="text-decoration-none">Login</a></span>
    </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/auth.php';
?>