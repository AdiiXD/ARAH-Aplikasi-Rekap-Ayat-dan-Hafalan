<?php

namespace App\Controllers;

use App\Models\SetoranHafalan;
use App\Models\Santri;
use App\Models\User;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Carbon\Carbon;

class StatisticsController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require(['ustadz', 'orangtua']);
    }

    public function weekly()
    {
        $userId = $_SESSION['user_id'];
        $role = $_SESSION['role'];

        if ($role === 'ustadz') {
            $santriIds = Santri::where('ustadz_id', $userId)->pluck('id')->toArray();
        } elseif ($role === 'orangtua') {
            $orangtua = User::find($userId);
            $santriIds = $orangtua->santrisAsOrangTua()->pluck('santri.id')->toArray();
        } else {
            $santriIds = [];
        }

        $weeks = [];
        $data = [];
        for ($i = 3; $i >= 0; $i--) {
            $start = Carbon::now()->subWeeks($i)->startOfWeek(Carbon::MONDAY);
            $end = (clone $start)->endOfWeek(Carbon::SUNDAY);
            $weeks[] = $start->format('d/m') . ' - ' . $end->format('d/m');
            $totalAyat = SetoranHafalan::whereIn('santri_id', $santriIds)
                ->whereBetween('tgl_setor', [$start, $end])
                ->sum('jumlah_ayat');
            $data[] = $totalAyat;
        }

        $title = "Statistik Hafalan Mingguan";
        $activeMenu = "statistics";
        ob_start();
        include __DIR__ . '/../../views/statistics/weekly.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }
}