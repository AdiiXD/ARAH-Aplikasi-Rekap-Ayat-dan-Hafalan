<?php

namespace App\Controllers;

use App\Models\User;
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

        $user = new User();
        $user->name = $_POST['name'];
        $user->email = $_POST['email'];
        $user->password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $user->role = 'ustadz';
        $user->save();

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

        $_SESSION['success'] = 'Ustadz berhasil diperbarui.';
        header('Location: index.php?action=admin/ustadz');
        exit;
    }

    public function destroy(int $id)
    {
        $ustadz = User::findOrFail($id);
        // Cek apakah ustadz memiliki santri
        if ($ustadz->santrisAsUstadz && $ustadz->santrisAsUstadz()->count() > 0) {
            $_SESSION['error'] = 'Ustadz tidak bisa dihapus karena masih membimbing santri.';
        } else {
            $ustadz->delete();
            $_SESSION['success'] = 'Ustadz berhasil dihapus.';
        }
        header('Location: index.php?action=admin/ustadz');
        exit;
    }
}