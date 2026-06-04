<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Santri;
use App\Models\OrangtuaSantri;
use Carbon\Carbon;

class RegisterController
{
    public function showForm()
    {
        // Jika sudah login, redirect ke dashboard
        if (isset($_SESSION['user_id'])) {
            $role = $_SESSION['role'];
            switch ($role) {
                case 'admin': header('Location: index.php?action=admin/dashboard'); break;
                case 'ustadz': header('Location: index.php?action=ustadz/dashboard'); break;
                case 'orangtua': header('Location: index.php?action=orangtua/dashboard'); break;
                default: header('Location: index.php?action=login');
            }
            exit;
        }
        include __DIR__ . '/../../views/auth/register.php';
    }

    public function register()
    {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $nis = trim($_POST['nis'] ?? '');
        $nickname = trim($_POST['nickname'] ?? '');

        // Validasi input
        $errors = [];
        if (empty($name)) $errors[] = "Nama lengkap wajib diisi.";
        if (empty($email)) $errors[] = "Email wajib diisi.";
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email tidak valid.";
        if (strlen($password) < 6) $errors[] = "Password minimal 6 karakter.";
        if ($password !== $confirm_password) $errors[] = "Password dan konfirmasi tidak cocok.";
        if (empty($nis)) $errors[] = "NIS santri wajib diisi.";
        if (empty($nickname)) $errors[] = "Nickname santri wajib diisi.";

        // Cek email sudah terdaftar
        if (User::where('email', $email)->exists()) {
            $errors[] = "Email sudah terdaftar.";
        }

        // Cari santri berdasarkan NIS dan nickname
        $santri = Santri::where('nis', $nis)->where('nickname', $nickname)->first();
        if (!$santri) {
            $errors[] = "Data santri tidak ditemukan. Pastikan NIS dan nickname sesuai.";
        }

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: index.php?action=register');
            exit;
        }

        // Buat akun orang tua
        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role = 'orangtua';
        $user->save();

        // Hubungkan dengan santri
        $exists = OrangtuaSantri::where('orangtua_id', $user->id)
            ->where('santri_id', $santri->id)
            ->exists();
        if (!$exists) {
            OrangtuaSantri::create([
                'orangtua_id' => $user->id,
                'santri_id' => $santri->id
            ]);
        }

        // Langsung login
        $_SESSION['user_id'] = $user->id;
        $_SESSION['name'] = $user->name;
        $_SESSION['role'] = $user->role;
        $_SESSION['email'] = $user->email;

        logActivity('Registrasi Orang Tua', "Akun orang tua baru: {$email} terhubung ke santri {$santri->nama} (NIS: {$nis})");

        header('Location: index.php?action=orangtua/dashboard');
        exit;
    }
}