<?php
$errorMessage = $error ?? 'Terjadi kesalahan. Silakan coba lagi.';
?>
<div class="card-custom p-4 text-center">
    <div class="alert alert-danger">
        <i class="bi bi-exclamation-triangle-fill"></i> 
        <?= htmlspecialchars($errorMessage) ?>
    </div>
    <a href="index.php?action=quran" class="btn btn-maroon">Kembali ke Daftar Surat</a>
</div>