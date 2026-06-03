<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\QuranQuote;
use App\Models\QuranListeningLog;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class OrangtuaDashboardController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('orangtua');
    }

    public function index()
    {
        // Ambil daftar anak (santri) yang terhubung
        $orangtua = User::find($_SESSION['user_id']);
        $anakList = $orangtua->santrisAsOrangTua()->with('kelas')->get();

        // Ambil quote harian
        $dailyQuote = QuranQuote::getDailyQuote();

        $todayAyatCount = QuranListeningLog::todayCount($_SESSION['user_id']);
        $todayDetails = QuranListeningLog::todayDetails($_SESSION['user_id']);

        // Kirim ke view
        include __DIR__ . '/../../views/orangtua/dashboard.php';
    }
}
