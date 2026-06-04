<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\ActivityLog;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Carbon\Carbon;
use Illuminate\Database\Capsule\Manager as Capsule;

class AdminDashboardController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
    }

    public function index()
    {
        // Statistik
        $totalSantri = Santri::count();
        $totalUstadz = User::where('role', 'ustadz')->count();
        $todaySetoran = SetoranHafalan::whereDate('tgl_setor', Carbon::today())->sum('jumlah_ayat');

        // Grafik perkembangan santri & ustadz per bulan (6 bulan terakhir)
        $months = [];
        $santriData = [];
        $ustadzData = [];
        for ($i = 5; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $months[] = $month->format('M Y');
            $santriData[] = Santri::whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
            $ustadzData[] = User::where('role', 'ustadz')
                ->whereYear('created_at', $month->year)
                ->whereMonth('created_at', $month->month)
                ->count();
        }

        // Top 5 ustadz berdasarkan total setoran ayat santri binaannya
        $topUstadz = User::where('role', 'ustadz')->get()->map(function ($ustadz) {
            $total = Santri::where('ustadz_id', $ustadz->id)
                ->with('setoranHafalan')
                ->get()
                ->sum(function ($s) {
                    return $s->setoranHafalan->sum('jumlah_ayat');
                });
            return (object)['name' => $ustadz->name, 'total_ayat' => $total];
        })->sortByDesc('total_ayat')->take(5)->values();

        // Perbandingan bulan ini vs bulan lalu
        $currentMonthStart = Carbon::now()->startOfMonth();
        $lastMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $lastMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        $totalAyatBulanIni = SetoranHafalan::whereBetween('tgl_setor', [$currentMonthStart, Carbon::now()])->sum('jumlah_ayat');
        $totalAyatBulanLalu = SetoranHafalan::whereBetween('tgl_setor', [$lastMonthStart, $lastMonthEnd])->sum('jumlah_ayat');

        // Tren setoran harian (30 hari)
        $endDate = Carbon::now();
        $startDate = $endDate->copy()->subDays(29);
        $dailySetoran = SetoranHafalan::whereBetween('tgl_setor', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_setor) as tanggal, SUM(jumlah_ayat) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal', 'asc')
            ->get()
            ->keyBy('tanggal');
        $dailyLabels = [];
        $dailyData = [];
        $period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->addDay());
        foreach ($period as $date) {
            $dailyLabels[] = $date->format('d M');
            $dailyData[] = $dailySetoran->has($date->format('Y-m-d')) ? $dailySetoran[$date->format('Y-m-d')]->total : 0;
        }

        // Log terbaru
        $recentLogs = ActivityLog::with('user')->orderBy('created_at', 'desc')->limit(10)->get();

        $title = "Dashboard Admin";
        $activeMenu = "dashboard";
        ob_start();
        include __DIR__ . '/../../views/admin/dashboard.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function backup()
    {
        $db = Capsule::connection()->getConfig();
        $host = $db['host'];
        $username = $db['username'];
        $password = $db['password'];
        $database = $db['database'];
        $backupFile = __DIR__ . '/../../backups/backup_' . date('Ymd_His') . '.sql';

        $backupDir = dirname($backupFile);
        if (!is_dir($backupDir)) mkdir($backupDir, 0755, true);

        $command = "mysqldump --host={$host} --user={$username} --password={$password} {$database} > {$backupFile}";
        system($command, $output);

        if (file_exists($backupFile) && filesize($backupFile) > 0) {
            logActivity('Backup database', 'Backup database berhasil');
            $_SESSION['success'] = "Backup berhasil disimpan di folder backups.";
        } else {
            $_SESSION['error'] = "Backup gagal. Pastikan mysqldump tersedia atau folder backups writable.";
        }
        header("Location: index.php?action=admin/dashboard");
        exit;
    }

    public function logs()
    {
        $logs = ActivityLog::with('user')->orderBy('created_at', 'desc')->limit(100)->get();
        $title = "Log Aktivitas";
        $activeMenu = "logs";
        ob_start();
        include __DIR__ . '/../../views/admin/logs.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function clearLogs()
    {
        ActivityLog::truncate();
        logActivity('Clear logs', 'Semua log aktivitas dihapus');
        $_SESSION['success'] = "Log aktivitas berhasil dihapus.";
        header("Location: index.php?action=admin/logs");
        exit;
    }
}
