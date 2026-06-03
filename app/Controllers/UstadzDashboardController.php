<?php

namespace App\Controllers;

use App\Models\Santri;
use App\Models\QuranQuote;
use App\Models\QuranListeningLog;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class UstadzDashboardController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('ustadz');
    }

    public function index()
    {
        // Ambil daftar santri binaan
        $santriList = Santri::with('kelas')
            ->where('ustadz_id', $_SESSION['user_id'])
            ->get();

        // Ambil quote harian
        $dailyQuote = QuranQuote::getDailyQuote();

        $todayAyatCount = QuranListeningLog::todayCount($_SESSION['user_id']);
        $todayDetails = QuranListeningLog::todayDetails($_SESSION['user_id']);

        // Kirim ke view
        include __DIR__ . '/../../views/ustadz/dashboard.php';
    }
}
