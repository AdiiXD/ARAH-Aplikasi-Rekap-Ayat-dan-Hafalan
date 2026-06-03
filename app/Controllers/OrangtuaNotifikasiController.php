<?php

namespace App\Controllers;

use App\Models\Notifikasi;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class OrangtuaNotifikasiController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('orangtua');
    }

    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', $_SESSION['user_id'])
            ->orderBy('created_at', 'desc')
            ->get();
        include __DIR__ . '/../../views/orangtua/notifikasi/index.php';
    }

    public function markAsRead(int $id)
    {
        $notif = Notifikasi::where('user_id', $_SESSION['user_id'])
            ->where('id', $id)
            ->firstOrFail();
        $notif->is_read = true;
        $notif->save();

        $_SESSION['success'] = 'Notifikasi ditandai sudah dibaca.';
        header('Location: index.php?action=orangtua/notifikasi');
        exit;
    }
}