<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Models\OrangtuaSantri;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class OrangtuaAnakController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('orangtua');
    }

    public function create()
    {
        include __DIR__ . '/../../views/orangtua/anak/create.php';
    }

    public function store()
    {
        $nis = trim($_POST['nis'] ?? '');
        $nickname = trim($_POST['nickname'] ?? '');
        $userId = $_SESSION['user_id'];

        if (empty($nis) || empty($nickname)) {
            $_SESSION['error'] = "NIS dan nickname harus diisi.";
            header('Location: index.php?action=orangtua/anak/create');
            exit;
        }

        // Cari santri berdasarkan NIS dan nickname (harus cocok persis)
        $santri = Santri::where('nis', $nis)->where('nickname', $nickname)->first();
        if (!$santri) {
            $_SESSION['error'] = "Data anak tidak ditemukan. Pastikan NIS dan nickname sesuai dengan data yang dibuat admin.";
            header('Location: index.php?action=orangtua/anak/create');
            exit;
        }

        // Cek apakah sudah terhubung
        $exists = OrangtuaSantri::where('orangtua_id', $userId)
            ->where('santri_id', $santri->id)
            ->exists();
        if ($exists) {
            $_SESSION['error'] = "Anak sudah terhubung dengan akun Anda.";
            header('Location: index.php?action=orangtua/anak/create');
            exit;
        }

        // Simpan relasi
        OrangtuaSantri::create([
            'orangtua_id' => $userId,
            'santri_id' => $santri->id
        ]);

        logActivity('Tambah Anak', "Orang tua ID {$userId} menambah santri {$santri->nama} (NIS: {$nis})");

        $_SESSION['success'] = "Berhasil menambahkan anak.";
        header('Location: index.php?action=orangtua/dashboard');
        exit;
    }
}