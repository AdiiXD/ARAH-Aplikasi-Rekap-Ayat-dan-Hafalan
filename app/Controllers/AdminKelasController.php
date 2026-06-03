<?php

namespace App\Controllers;

use App\Models\Kelas;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class AdminKelasController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
    }

    public function index()
    {
        $kelas = Kelas::orderBy('id', 'desc')->get();
        include __DIR__ . '/../../views/admin/kelas/index.php';
    }

    public function create()
    {
        include __DIR__ . '/../../views/admin/kelas/create.php';
    }

    public function store()
    {
        $kelas = new Kelas();
        $kelas->nama_kelas = $_POST['nama_kelas'];
        $kelas->deskripsi = $_POST['deskripsi'] ?? null;
        $kelas->save();

        $_SESSION['success'] = 'Kelas berhasil ditambahkan.';
        header('Location: index.php?action=admin/kelas');
        exit;
    }

    public function edit(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        include __DIR__ . '/../../views/admin/kelas/edit.php';
    }

    public function update(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas->nama_kelas = $_POST['nama_kelas'];
        $kelas->deskripsi = $_POST['deskripsi'] ?? null;
        $kelas->save();

        $_SESSION['success'] = 'Kelas berhasil diperbarui.';
        header('Location: index.php?action=admin/kelas');
        exit;
    }

    public function destroy(int $id)
    {
        $kelas = Kelas::findOrFail($id);
        // Cek apakah kelas memiliki santri
        if ($kelas->santris()->count() > 0) {
            $_SESSION['error'] = 'Kelas tidak bisa dihapus karena masih memiliki santri.';
        } else {
            $kelas->delete();
            $_SESSION['success'] = 'Kelas berhasil dihapus.';
        }
        header('Location: index.php?action=admin/kelas');
        exit;
    }
}