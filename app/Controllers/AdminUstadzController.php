<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Santri;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class AdminUstadzController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
    }

    public function index()
    {
        $ustadz = User::where('role', 'ustadz')->orderBy('id', 'desc')->get();
        include __DIR__ . '/../../views/admin/ustadz/index.php';
    }

    public function create()
    {
        include __DIR__ . '/../../views/admin/ustadz/create.php';
    }

    public function store()
    {
        $existing = User::where('email', $_POST['email'])->first();
        if ($existing) {
            $_SESSION['error'] = 'Email sudah terdaftar.';
            header('Location: index.php?action=admin/ustadz/create');
            exit;
        }

        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $user = new User();
        $user->name = $name;
        $user->email = $email;
        $user->password = password_hash($password, PASSWORD_DEFAULT);
        $user->role = 'ustadz';
        $user->save();

        logActivity('Tambah Ustadz', "Menambah ustadz baru: {$name} ({$email})");

        $_SESSION['success'] = 'Ustadz berhasil ditambahkan.';
        header('Location: index.php?action=admin/ustadz');
        exit;
    }

    public function edit(int $id)
    {
        $ustadz = User::findOrFail($id);
        if ($ustadz->role !== 'ustadz') {
            $_SESSION['error'] = 'User bukan ustadz.';
            header('Location: index.php?action=admin/ustadz');
            exit;
        }
        include __DIR__ . '/../../views/admin/ustadz/edit.php';
    }

    public function update(int $id)
    {
        $ustadz = User::findOrFail($id);
        $ustadz->name = $_POST['name'];
        if (!empty($_POST['password'])) {
            $ustadz->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        }
        $ustadz->save();

        logActivity('Update Ustadz', "Mengupdate ustadz ID {$id} - {$ustadz->name}");

        $_SESSION['success'] = 'Ustadz berhasil diperbarui.';
        header('Location: index.php?action=admin/ustadz');
        exit;
    }

    public function destroy(int $id)
    {
        $ustadz = User::findOrFail($id);
        $name = $ustadz->name;
        if ($ustadz->santrisAsUstadz && $ustadz->santrisAsUstadz()->count() > 0) {
            $_SESSION['error'] = 'Ustadz tidak bisa dihapus karena masih membimbing santri.';
        } else {
            $ustadz->delete();
            logActivity('Hapus Ustadz', "Menghapus ustadz: {$name} (ID {$id})");
            $_SESSION['success'] = 'Ustadz berhasil dihapus.';
        }
        header('Location: index.php?action=admin/ustadz');
        exit;
    }
}