<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Models\TargetHafalan;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class UstadzTargetController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('ustadz');
    }

    // Tambah int
    public function index(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);
        $targets = TargetHafalan::where('santri_id', $santriId)->get();
        include __DIR__ . '/../../views/ustadz/target/index.php';
    }

    // Tambah int
    public function create(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);
        include __DIR__ . '/../../views/ustadz/target/create.php';
    }

    // Tambah int
    public function store(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);
        TargetHafalan::create([
            'santri_id' => $santriId,
            'target_ayat' => $_POST['target_ayat'],
            'deadline' => $_POST['deadline']
        ]);
        $_SESSION['success'] = 'Target hafalan ditambahkan.';
        header("Location: index.php?action=ustadz/target/index&id=$santriId");
        exit;
    }

    // Tambah int
    public function edit(int $id)
    {
        $target = TargetHafalan::with('santri')->findOrFail($id);
        if ($target->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        include __DIR__ . '/../../views/ustadz/target/edit.php';
    }

    // Tambah int
    public function update(int $id)
    {
        $target = TargetHafalan::with('santri')->findOrFail($id);
        if ($target->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $target->target_ayat = $_POST['target_ayat'];
        $target->deadline = $_POST['deadline'];
        $target->save();
        $_SESSION['success'] = 'Target diperbarui.';
        header("Location: index.php?action=ustadz/target/index&id=" . $target->santri_id);
        exit;
    }

    // Tambah int
    public function destroy(int $id)
    {
        $target = TargetHafalan::with('santri')->findOrFail($id);
        if ($target->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $santriId = $target->santri_id;
        $target->delete();
        $_SESSION['success'] = 'Target dihapus.';
        header("Location: index.php?action=ustadz/target/index&id=$santriId");
        exit;
    }
}