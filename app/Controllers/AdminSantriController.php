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

    public function show(int $id)
    {
        $santri = Santri::with(['ustadz', 'kelas', 'targetHafalan', 'setoranHafalan', 'setoranMurajaah'])
            ->findOrFail($id);
        include __DIR__ . '/../../views/admin/santri/show.php';
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
        $santri->nis = $_POST['nis'];
        $santri->nama = $_POST['nama'];
        $santri->nickname = $_POST['nickname'];
        $santri->tanggal_lahir = $_POST['tanggal_lahir'];
        $santri->tahun_masuk = $_POST['tahun_masuk'];
        $santri->ustadz_id = $_POST['ustadz_id'];
        $santri->kelas_id = $_POST['kelas_id'];
        $santri->save();

        logActivity('Tambah Santri', "Menambah santri: {$santri->nama} (ID {$santri->id})");

        $_SESSION['success'] = 'Santri berhasil ditambahkan.';
        header('Location: index.php?action=admin/santri');
        exit;
    }

    public function edit(int $id)
    {
        $santri = Santri::findOrFail($id);
        $ustadzList = User::where('role', 'ustadz')->get();
        $kelasList = Kelas::all();
        include __DIR__ . '/../../views/admin/santri/edit.php';
    }

    public function update(int $id)
    {
        $santri = Santri::findOrFail($id);
        $oldName = $santri->nama;
        $santri->nis = $_POST['nis'];
        $santri->nama = $_POST['nama'];
        $santri->nickname = $_POST['nickname'];
        $santri->tanggal_lahir = $_POST['tanggal_lahir'];
        $santri->tahun_masuk = $_POST['tahun_masuk'];
        $santri->ustadz_id = $_POST['ustadz_id'];
        $santri->kelas_id = $_POST['kelas_id'];
        $santri->save();

        logActivity('Update Santri', "Mengupdate santri ID {$id} dari '{$oldName}' menjadi '{$santri->nama}'");

        $_SESSION['success'] = 'Santri berhasil diperbarui.';
        header('Location: index.php?action=admin/santri');
        exit;
    }

    public function destroy(int $id)
    {
        $santri = Santri::findOrFail($id);
        $name = $santri->nama;
        $santri->delete();

        logActivity('Hapus Santri', "Menghapus santri: {$name} (ID {$id})");

        $_SESSION['success'] = 'Santri berhasil dihapus.';
        header('Location: index.php?action=admin/santri');
        exit;
    }
}
