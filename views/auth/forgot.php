<?php
$title = "Lupa Password - ARAH";
ob_start();
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-custom"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-custom"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form method="POST" action="index.php?action=sendResetLink">
    <p class="text-muted small mb-3">Masukkan email Anda, kami akan mengirimkan link untuk reset password.</p>
    <div class="mb-4">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required>
    </div>
    <button type="submit" class="btn btn-maroon">Kirim Link Reset</button>
    <hr>
    <div class="text-center">
        <a href="index.php?action=login" class="text-decoration-none small">Kembali ke Login</a>
    </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/auth.php';
?>