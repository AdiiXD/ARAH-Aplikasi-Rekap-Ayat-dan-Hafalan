<?php
$title = "Login - ARAH";
ob_start();
?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-custom"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>
<?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-custom"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>

<form method="POST" action="index.php?action=login">
    <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" placeholder="nama@email.com" required autofocus>
    </div>
    <div class="mb-4">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" placeholder="********" required>
    </div>
    <button type="submit" class="btn btn-maroon">Masuk</button>
    <hr>
    <div class="text-center">
        <a href="index.php?action=forgot" class="text-decoration-none small">Lupa password?</a>
    </div>
    <div class="text-center mt-2">
        <span class="small text-muted">Belum punya akun? <a href="index.php?action=register" class="text-decoration-none">Daftar sebagai Orang Tua</a></span>
    </div>
</form>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/auth.php';
?>