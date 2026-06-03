<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class UstadzSantriController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('ustadz');
    }

    public function index()
    {
        $santriList = Santri::with(['kelas', 'targetHafalan'])
            ->where('ustadz_id', $_SESSION['user_id'])
            ->get();
        include __DIR__ . '/../../views/ustadz/santri/index.php';
    }

    // PERBAIKAN: tambah int type hint
    public function show(int $id)
    {
        $santri = Santri::with(['ustadz', 'kelas', 'setoranHafalan', 'setoranMurajaah', 'targetHafalan'])
            ->where('ustadz_id', $_SESSION['user_id'])
            ->findOrFail($id);
        include __DIR__ . '/../../views/ustadz/santri/show.php';
    }
}