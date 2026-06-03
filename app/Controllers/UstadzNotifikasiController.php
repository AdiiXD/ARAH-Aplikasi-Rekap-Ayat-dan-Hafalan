<?php

namespace App\Controllers;

use App\Models\Notifikasi;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class UstadzNotifikasiController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('ustadz');
    }

    public function index()
    {
        $notifikasi = Notifikasi::where('user_id', $_SESSION['user_id'])
            ->orderBy('created_at', 'desc')
            ->get();
        $title = "Notifikasi Ustadz";
        $activeMenu = "notifikasi";
        ob_start();
        include __DIR__ . '/../../views/ustadz/notifikasi/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function markAsRead(int $id)
    {
        $notif = Notifikasi::where('user_id', $_SESSION['user_id'])
            ->where('id', $id)
            ->firstOrFail();
        $notif->is_read = true;
        $notif->save();
        $_SESSION['success'] = 'Notifikasi ditandai sudah dibaca.';
        header('Location: index.php?action=ustadz/notifikasi');
        exit;
    }
}