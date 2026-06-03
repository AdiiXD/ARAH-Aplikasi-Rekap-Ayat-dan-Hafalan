<?php

/** @var \App\Models\User $user */
/** @var array $preferences */
$title = "Pengaturan Akun";
$activeMenu = "profile";
?>
<div class="row">
    <div class="col-12">
        <div class="card-custom p-4 mb-4">
            <h3><i class="bi bi-person-circle"></i> Profil Saya</h3>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Kolom Foto Profil -->
    <div class="col-md-4">
        <div class="card-custom p-4 text-center h-100">
            <img src="<?= $user->getProfilePictureUrl() ?>" alt="Profile Picture" class="rounded-circle img-fluid mb-3" style="width: 150px; height: 150px; object-fit: cover; border: 3px solid #4A1D2E; margin: 0 auto;">
            <h5><?= htmlspecialchars($user->name) ?></h5>
            <p class="text-muted"><?= htmlspecialchars($user->email) ?></p>
            <hr>
            <form method="POST" action="index.php?action=profile/upload-picture" enctype="multipart/form-data">
                <div class="mb-2">
                    <input type="file" name="profile_picture" class="form-control form-control-sm" accept="image/jpeg,image/png,image/jpg" required>
                </div>
                <button type="submit" class="btn btn-sm btn-maroon">Upload Foto</button>
            </form>
            <small class="text-muted">Format JPG/PNG, max 2MB</small>
        </div>
    </div>

    <!-- Kolom Edit Profil -->
    <div class="col-md-8">
        <div class="card-custom p-4 h-100">
            <h5><i class="bi bi-pencil-square"></i> Edit Profil</h5>
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success mt-2"><?= $_SESSION['success'];
                                                        unset($_SESSION['success']); ?></div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger mt-2"><?= $_SESSION['error'];
                                                        unset($_SESSION['error']); ?></div>
            <?php endif; ?>
            <form method="POST" action="index.php?action=profile/update">
                <div class="mb-3">
                    <label class="form-label">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($user->name) ?>" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($user->email) ?>" required>
                </div>
                <button type="submit" class="btn btn-maroon">Update Profil</button>
            </form>
        </div>
    </div>
</div>

<!-- Keamanan -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card-custom p-4">
            <h5><i class="bi bi-shield-lock"></i> Keamanan</h5>
            <form method="POST" action="index.php?action=profile/change-password" class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Password Lama</label>
                    <input type="password" name="old_password" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Password Baru</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" name="confirm_password" class="form-control" required>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-maroon">Ganti Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Preferensi Aplikasi -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card-custom p-4">
            <h5><i class="bi bi-sliders2"></i> Preferensi Aplikasi</h5>
            <form method="POST" action="index.php?action=profile/preferences">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Qari Default (Al-Qur'an)</label>
                        <select name="default_qari" class="form-select">
                            <option value="ar.alafasy" <?= $preferences['default_qari'] == 'ar.alafasy' ? 'selected' : '' ?>>Mishary Alafasy</option>
                            <option value="ar.abdulbasitmurattal" <?= $preferences['default_qari'] == 'ar.abdulbasitmurattal' ? 'selected' : '' ?>>Abdul Basit</option>
                            <option value="ar.mahermuaiqly" <?= $preferences['default_qari'] == 'ar.mahermuaiqly' ? 'selected' : '' ?>>Maher Al Muaiqly</option>
                        </select>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch mt-4">
                            <input class="form-check-input" type="checkbox" name="default_tajwid" id="defaultTajwid" value="1" <?= $preferences['default_tajwid'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="defaultTajwid">Aktifkan Tajwid Warna (default)</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="default_translation" id="defaultTranslation" value="1" <?= $preferences['default_translation'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="defaultTranslation">Tampilkan Terjemahan (default)</label>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="notif_email_enabled" id="notifEmail" value="1" <?= $preferences['notif_email_enabled'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="notifEmail">Terima Notifikasi Email</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-maroon">Simpan Preferensi</button>
            </form>
        </div>
    </div>
</div>