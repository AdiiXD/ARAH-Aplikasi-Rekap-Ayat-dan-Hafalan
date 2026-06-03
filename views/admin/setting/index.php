<?php
$title = "Pengaturan Sistem";
$activeMenu = "settings";
// Jika $globalSettings tidak didefinisikan, beri default
if (!isset($globalSettings)) {
    $globalSettings = [
        'app_name' => 'Hafalan Tracker',
        'app_timezone' => 'Asia/Jakarta',
        'date_format' => 'd-m-Y',
        'notif_email_enabled' => true,
        'reminder_days_before' => 3,
        'default_qari' => 'ar.alafasy',
        'default_tajwid' => false,
        'default_translation' => true,
    ];
}
?>
<div class="card-custom p-4">
    <h3><i class="bi bi-gear"></i> Pengaturan Sistem</h3>
    <p class="text-muted">Pengaturan global yang mempengaruhi semua pengguna.</p>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    
    <form method="POST" action="index.php?action=admin/settings/update">
        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Nama Aplikasi</label>
                <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($globalSettings['app_name']) ?>">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Zona Waktu</label>
                <select name="app_timezone" class="form-select">
                    <?php $timezones = ['Asia/Jakarta', 'Asia/Makassar', 'Asia/Jayapura']; ?>
                    <?php foreach ($timezones as $tz): ?>
                    <option value="<?= $tz ?>" <?= $globalSettings['app_timezone'] == $tz ? 'selected' : '' ?>><?= $tz ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Format Tanggal</label>
                <input type="text" name="date_format" class="form-control" value="<?= htmlspecialchars($globalSettings['date_format']) ?>" placeholder="d-m-Y">
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Pengingat Deadline (hari)</label>
                <input type="number" name="reminder_days_before" class="form-control" value="<?= $globalSettings['reminder_days_before'] ?>">
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="notif_email_enabled" id="notifEmail" value="1" <?= $globalSettings['notif_email_enabled'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="notifEmail">Aktifkan Notifikasi Email</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <label class="form-label">Qari Default</label>
                <select name="default_qari" class="form-select">
                    <option value="ar.alafasy" <?= $globalSettings['default_qari'] == 'ar.alafasy' ? 'selected' : '' ?>>Mishary Alafasy</option>
                    <option value="ar.abdulbasitmurattal" <?= $globalSettings['default_qari'] == 'ar.abdulbasitmurattal' ? 'selected' : '' ?>>Abdul Basit</option>
                    <option value="ar.mahermuaiqly" <?= $globalSettings['default_qari'] == 'ar.mahermuaiqly' ? 'selected' : '' ?>>Maher Al Muaiqly</option>
                </select>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="default_tajwid" id="defaultTajwid" value="1" <?= $globalSettings['default_tajwid'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="defaultTajwid">Tajwid Warna (default)</label>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="form-check form-switch">
                    <input class="form-check-input" type="checkbox" name="default_translation" id="defaultTranslation" value="1" <?= $globalSettings['default_translation'] ? 'checked' : '' ?>>
                    <label class="form-check-label" for="defaultTranslation">Tampilkan Terjemahan (default)</label>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-maroon">Simpan Pengaturan</button>
    </form>
</div>