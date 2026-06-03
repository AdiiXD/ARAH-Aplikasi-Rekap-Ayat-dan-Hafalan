<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Models\User;
use App\Models\Kelas;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class AdminSantriController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
    }

    public function index()
    {
        $santri = Santri::with(['ustadz', 'kelas'])->orderBy('id', 'desc')->get();
        include __DIR__ . '/../../views/admin/santri/index.php';
    }

    public function create()
    {
        $ustadzList = User::where('role', 'ustadz')->get();
        $kelasList = Kelas::all();
        include __DIR__ . '/../../views/admin/santri/create.php';
    }

    public function store()
    {
        $santri = new Santri();
        $santri->nama = $_POST['nama'];
        $santri->tanggal_lahir = $_POST['tanggal_lahir'];
        $santri->tahun_masuk = $_POST['tahun_masuk'];
        $santri->ustadz_id = $_POST['ustadz_id'];
        $santri->kelas_id = $_POST['kelas_id'];
        $santri->save();

        $_SESSION['success'] = 'Santri berhasil ditambahkan.';
        header('Location: index.php?action=admin/santri');
        exit;
    }

    // PERBAIKAN: tambah type hint int
    public function edit(int $id)
    {
        $santri = Santri::findOrFail($id);
        $ustadzList = User::where('role', 'ustadz')->get();
        $kelasList = Kelas::all();
        include __DIR__ . '/../../views/admin/santri/edit.php';
    }

    // PERBAIKAN: tambah type hint int
    public function update(int $id)
    {
        $santri = Santri::findOrFail($id);
        $santri->nama = $_POST['nama'];
        $santri->tanggal_lahir = $_POST['tanggal_lahir'];
        $santri->tahun_masuk = $_POST['tahun_masuk'];
        $santri->ustadz_id = $_POST['ustadz_id'];
        $santri->kelas_id = $_POST['kelas_id'];
        $santri->save();

        $_SESSION['success'] = 'Santri berhasil diperbarui.';
        header('Location: index.php?action=admin/santri');
        exit;
    }

    // PERBAIKAN: tambah type hint int
    public function destroy(int $id)
    {
        $santri = Santri::findOrFail($id);
        // Hapus relasi orangtua_santri dulu (foreign key cascade akan otomatis jika di migration sudah onDelete cascade)
        $santri->delete();
        $_SESSION['success'] = 'Santri berhasil dihapus.';
        header('Location: index.php?action=admin/santri');
        exit;
    }
    public function show(int $id)
    {
        $santri = Santri::with(['ustadz', 'kelas', 'targetHafalan', 'setoranHafalan', 'setoranMurajaah', 'orangTua'])
            ->findOrFail($id);
        include __DIR__ . '/../../views/admin/santri/show.php';
    }
}
