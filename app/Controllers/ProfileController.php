<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Setting;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class ProfileController
{
    public function __construct()
    {
        AuthMiddleware::check();
        // Semua role yang login bisa akses
    }

    public function index()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findOrFail($userId);

        // Ambil preferensi user
        $preferences = [
            'default_qari' => Setting::get('default_qari', 'ar.alafasy', $userId),
            'default_tajwid' => Setting::get('default_tajwid', false, $userId),
            'default_translation' => Setting::get('default_translation', true, $userId),
            'notif_email_enabled' => Setting::get('notif_email_enabled', true, $userId),
        ];

        $title = "Pengaturan Akun";
        $activeMenu = "profile";
        ob_start();
        include __DIR__ . '/../../views/profile/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function updateProfile()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findOrFail($userId);

        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if (empty($name) || empty($email)) {
            $_SESSION['error'] = "Nama dan email wajib diisi.";
            header("Location: index.php?action=profile");
            exit;
        }

        // Cek email unik (kecuali milik sendiri)
        $exists = User::where('email', $email)->where('id', '!=', $userId)->exists();
        if ($exists) {
            $_SESSION['error'] = "Email sudah digunakan pengguna lain.";
            header("Location: index.php?action=profile");
            exit;
        }

        $user->name = $name;
        $user->email = $email;
        $user->save();

        // Update session
        $_SESSION['name'] = $user->name;
        $_SESSION['email'] = $user->email;

        $_SESSION['success'] = "Profil berhasil diperbarui.";
        header("Location: index.php?action=profile");
        exit;
    }

    public function updateAvatar()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findOrFail($userId);

        if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif'];
            $fileType = mime_content_type($_FILES['avatar']['tmp_name']);
            if (!in_array($fileType, $allowed)) {
                $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, PNG, atau GIF.";
                header("Location: index.php?action=profile");
                exit;
            }

            $maxSize = 2 * 1024 * 1024; // 2MB
            if ($_FILES['avatar']['size'] > $maxSize) {
                $_SESSION['error'] = "Ukuran file maksimal 2MB.";
                header("Location: index.php?action=profile");
                exit;
            }

            // Hapus avatar lama jika ada
            if ($user->avatar && file_exists($_SERVER['DOCUMENT_ROOT'] . $user->avatar)) {
                unlink($_SERVER['DOCUMENT_ROOT'] . $user->avatar);
            }

            $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
            $filename = 'avatar_' . $userId . '_' . time() . '.' . $ext;
            $uploadDir = '/uploads/avatars/';
            $absoluteDir = $_SERVER['DOCUMENT_ROOT'] . $uploadDir;
            if (!is_dir($absoluteDir)) {
                mkdir($absoluteDir, 0777, true);
            }
            $destination = $absoluteDir . $filename;
            if (move_uploaded_file($_FILES['avatar']['tmp_name'], $destination)) {
                $user->avatar = $uploadDir . $filename;
                $user->save();
                $_SESSION['success'] = "Foto profil berhasil diupdate.";
            } else {
                $_SESSION['error'] = "Gagal mengupload foto.";
            }
        } else {
            $_SESSION['error'] = "Pilih file foto terlebih dahulu.";
        }
        header("Location: index.php?action=profile");
        exit;
    }

    public function changePassword()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findOrFail($userId);

        $currentPassword = $_POST['current_password'] ?? '';
        $newPassword = $_POST['new_password'] ?? '';
        $confirmPassword = $_POST['confirm_password'] ?? '';

        if (empty($currentPassword) || empty($newPassword) || empty($confirmPassword)) {
            $_SESSION['error'] = "Semua field password harus diisi.";
            header("Location: index.php?action=profile");
            exit;
        }

        if (!password_verify($currentPassword, $user->password)) {
            $_SESSION['error'] = "Password saat ini salah.";
            header("Location: index.php?action=profile");
            exit;
        }

        if ($newPassword !== $confirmPassword) {
            $_SESSION['error'] = "Password baru dan konfirmasi tidak cocok.";
            header("Location: index.php?action=profile");
            exit;
        }

        if (strlen($newPassword) < 6) {
            $_SESSION['error'] = "Password baru minimal 6 karakter.";
            header("Location: index.php?action=profile");
            exit;
        }

        $user->password = password_hash($newPassword, PASSWORD_DEFAULT);
        $user->save();

        $_SESSION['success'] = "Password berhasil diubah.";
        header("Location: index.php?action=profile");
        exit;
    }

    public function updatePreferences()
    {
        $userId = $_SESSION['user_id'];

        $prefKeys = ['default_qari', 'default_tajwid', 'default_translation', 'notif_email_enabled'];
        foreach ($prefKeys as $key) {
            $value = $_POST[$key] ?? null;
            if ($value !== null) {
                $type = in_array($key, ['default_tajwid', 'default_translation', 'notif_email_enabled']) ? 'boolean' : 'text';
                if ($type === 'boolean') {
                    $value = (bool) $value;
                }
                Setting::set($key, $value, $userId, $type);
            }
        }

        $_SESSION['success'] = "Preferensi berhasil disimpan.";
        header("Location: index.php?action=profile");
        exit;
    }

    public function uploadPicture()
    {
        $userId = $_SESSION['user_id'];
        $user = User::findOrFail($userId);

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $_SESSION['error'] = "Method tidak diizinkan.";
            header("Location: index.php?action=profile");
            exit;
        }

        if (!isset($_FILES['profile_picture']) || $_FILES['profile_picture']['error'] === UPLOAD_ERR_NO_FILE) {
            $_SESSION['error'] = "Silakan pilih file terlebih dahulu.";
            header("Location: index.php?action=profile");
            exit;
        }

        $file = $_FILES['profile_picture'];
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $maxSize = 2 * 1024 * 1024; // 2MB

        // Validasi error upload
        if ($file['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['error'] = "Gagal upload: " . $this->uploadErrorMessage($file['error']);
            header("Location: index.php?action=profile");
            exit;
        }

        // Validasi tipe file
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        if (!in_array($mimeType, $allowedTypes)) {
            $_SESSION['error'] = "Format file tidak didukung. Gunakan JPG, JPEG, atau PNG.";
            header("Location: index.php?action=profile");
            exit;
        }

        // Validasi ukuran
        if ($file['size'] > $maxSize) {
            $_SESSION['error'] = "Ukuran file maksimal 2MB.";
            header("Location: index.php?action=profile");
            exit;
        }

        // Buat direktori jika belum ada
        $uploadDir = __DIR__ . '/../../public/uploads/profile/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // Hapus foto lama jika ada
        if ($user->profile_picture && file_exists($uploadDir . $user->profile_picture)) {
            unlink($uploadDir . $user->profile_picture);
        }

        // Generate nama file unik
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'user_' . $userId . '_' . time() . '.' . $extension;
        $destination = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $destination)) {
            $user->profile_picture = $filename;
            $user->save();
            $_SESSION['success'] = "Foto profil berhasil diubah.";
        } else {
            $_SESSION['error'] = "Gagal menyimpan file. Coba lagi.";
        }

        header("Location: index.php?action=profile");
        exit;
    }

    private function uploadErrorMessage(int $code): string
    {
        switch ($code) {
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return "Ukuran file terlalu besar.";
            case UPLOAD_ERR_PARTIAL:
                return "File hanya terupload sebagian.";
            case UPLOAD_ERR_NO_FILE:
                return "Tidak ada file yang dipilih.";
            case UPLOAD_ERR_NO_TMP_DIR:
                return "Folder temporary tidak ditemukan.";
            case UPLOAD_ERR_CANT_WRITE:
                return "Gagal menulis file ke disk.";
            case UPLOAD_ERR_EXTENSION:
                return "Upload dihentikan oleh ekstensi PHP.";
            default:
                return "Kesalahan tidak dikenal.";
        }
    }
}
