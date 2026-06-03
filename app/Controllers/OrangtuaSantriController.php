<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Models\User;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class OrangtuaSantriController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('orangtua');
    }

    // Menampilkan daftar anak (bisa juga di dashboard, tapi kita buat terpisah)
    public function index()
    {
        $orangtua = User::find($_SESSION['user_id']);
        $anakList = $orangtua->santrisAsOrangTua()->with('kelas')->get();
        include __DIR__ . '/../../views/orangtua/santri/index.php';
    }

    // Menampilkan progress detail seorang anak
    public function show(int $id)
    {
        $orangtua = User::find($_SESSION['user_id']);
        $santri = $orangtua->santrisAsOrangTua()
            ->with(['kelas', 'ustadz', 'setoranHafalan', 'setoranMurajaah', 'targetHafalan'])
            ->findOrFail($id);

        include __DIR__ . '/../../views/orangtua/santri/show.php';
    }
}
