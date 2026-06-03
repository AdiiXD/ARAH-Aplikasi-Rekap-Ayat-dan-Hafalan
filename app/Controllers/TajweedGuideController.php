<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class TajweedGuideController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require(['ustadz', 'orangtua']);
    }

    public function index()
    {
        $title = "Panduan Tajwid Warna";
        $activeMenu = "tajweed_guide";
        ob_start();
        include __DIR__ . '/../../views/tajweed-guide/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }
}