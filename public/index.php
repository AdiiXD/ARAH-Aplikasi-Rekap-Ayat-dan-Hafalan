<?php
session_start();
require_once __DIR__ . '/../app/bootstrap.php';

use App\Controllers\AuthController;
use App\Controllers\AdminDashboardController;
use App\Controllers\UstadzDashboardController;
use App\Controllers\OrangtuaDashboardController;
use App\Controllers\AdminKelasController;
use App\Controllers\AdminUstadzController;
use App\Controllers\AdminSantriController;

$action = $_GET['action'] ?? 'login';

switch ($action) {
    // Auth routes
    case 'login':
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->login();
        } else {
            $ctrl->showLogin();
        }
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'forgot':
        $ctrl = new AuthController();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $ctrl->sendResetLink();
        } else {
            $ctrl->forgot();
        }
        break;
    // Dashboard
    case 'admin/dashboard':
        (new AdminDashboardController())->index();
        break;
    case 'ustadz/dashboard':
        (new UstadzDashboardController())->index();
        break;
    case 'orangtua/dashboard':
        (new OrangtuaDashboardController())->index();
        break;
    // CRUD Kelas
    case 'admin/kelas':
        (new AdminKelasController())->index();
        break;
    case 'admin/kelas/create':
        (new AdminKelasController())->create();
        break;
    case 'admin/kelas/store':
        (new AdminKelasController())->store();
        break;
    case 'admin/kelas/edit':
        (new AdminKelasController())->edit((int)$_GET['id']);
        break;
    case 'admin/kelas/update':
        (new AdminKelasController())->update((int)$_GET['id']);
        break;
    case 'admin/kelas/delete':
        (new AdminKelasController())->destroy((int)$_GET['id']);
        break;
    // CRUD Ustadz
    case 'admin/ustadz':
        (new AdminUstadzController())->index();
        break;
    case 'admin/ustadz/create':
        (new AdminUstadzController())->create();
        break;
    case 'admin/ustadz/store':
        (new AdminUstadzController())->store();
        break;
    case 'admin/ustadz/edit':
        (new AdminUstadzController())->edit((int)$_GET['id']);
        break;
    case 'admin/ustadz/update':
        (new AdminUstadzController())->update((int)$_GET['id']);
        break;
    case 'admin/ustadz/delete':
        (new AdminUstadzController())->destroy((int)$_GET['id']);
        break;
    case 'admin/santri':
        (new \App\Controllers\AdminSantriController())->index();
        break;
    // CRUD Santri
    case 'ustadz/santri':
        (new \App\Controllers\UstadzSantriController())->index();
        break;
    case 'ustadz/santri/show':
        (new \App\Controllers\UstadzSantriController())->show((int)$_GET['id']);
        break;
    // Setoran Hafalan
    case 'ustadz/setoran/create_hafalan':
        (new \App\Controllers\UstadzSetoranController())->createHafalan((int)$_GET['id']);
        break;
    case 'ustadz/setoran/store_hafalan':
        (new \App\Controllers\UstadzSetoranController())->storeHafalan((int)$_GET['id']);
        break;
    case 'ustadz/setoran/edit_hafalan':
        (new \App\Controllers\UstadzSetoranController())->editHafalan((int)$_GET['id']);
        break;
    case 'ustadz/setoran/update_hafalan':
        (new \App\Controllers\UstadzSetoranController())->updateHafalan((int)$_GET['id']);
        break;
    case 'ustadz/setoran/destroy_hafalan':
        (new \App\Controllers\UstadzSetoranController())->destroyHafalan((int)$_GET['id']);
        break;
    // Setoran Murajaah (sama polanya)
    case 'ustadz/setoran/create_murajaah':
        (new \App\Controllers\UstadzSetoranController())->createMurajaah((int)$_GET['id']);
        break;
    case 'ustadz/setoran/store_murajaah':
        (new \App\Controllers\UstadzSetoranController())->storeMurajaah((int)$_GET['id']);
        break;
    // Target Hafalan
    case 'ustadz/target/index':
        (new \App\Controllers\UstadzTargetController())->index((int)$_GET['id']);
        break;
    case 'ustadz/target/create':
        (new \App\Controllers\UstadzTargetController())->create((int)$_GET['id']);
        break;
    case 'ustadz/target/store':
        (new \App\Controllers\UstadzTargetController())->store((int)$_GET['id']);
        break;
    case 'ustadz/target/edit':
        (new \App\Controllers\UstadzTargetController())->edit((int)$_GET['id']);
        break;
    case 'ustadz/target/update':
        (new \App\Controllers\UstadzTargetController())->update((int)$_GET['id']);
        break;
    case 'ustadz/target/destroy':
        (new \App\Controllers\UstadzTargetController())->destroy((int)$_GET['id']);
        break;
    // Dashboard Orang Tua
    case 'orangtua/santri':
        (new \App\Controllers\OrangtuaSantriController())->index();
        break;
    case 'orangtua/santri/show':
        (new \App\Controllers\OrangtuaSantriController())->show((int)$_GET['id']);
        break;
    case 'orangtua/notifikasi':
        (new \App\Controllers\OrangtuaNotifikasiController())->index();
        break;
    case 'orangtua/notifikasi/markAsRead':
        (new \App\Controllers\OrangtuaNotifikasiController())->markAsRead((int)$_GET['id']);
        break;
    // Laporan
    case 'reports':
        (new \App\Controllers\ReportController())->index();
        break;
    // Admin reports
    case 'admin/report/excel':
        (new \App\Controllers\ReportController())->adminExportExcel();
        break;
    case 'admin/report/pdf':
        (new \App\Controllers\ReportController())->adminExportPdf();
        break;
    // Ustadz reports
    case 'ustadz/report/pdf':
        (new \App\Controllers\ReportController())->ustadzExportPdf();
        break;
    case 'ustadz/report/excel':
        (new \App\Controllers\ReportController())->ustadzExportExcel();
        break;
    // Orang tua reports
    case 'orangtua/report/pdf':
        (new \App\Controllers\ReportController())->orangtuaExportPdf();
        break;
    case 'ustadz/notifikasi':
        (new \App\Controllers\UstadzNotifikasiController())->index();
        break;
    case 'ustadz/notifikasi/markAsRead':
        (new \App\Controllers\UstadzNotifikasiController())->markAsRead((int)$_GET['id']);
        break;
    case 'admin/santri/create':
        (new \App\Controllers\AdminSantriController())->create();
        break;
    case 'admin/santri/store':
        (new \App\Controllers\AdminSantriController())->store();
        break;
    case 'admin/santri/edit':
        (new \App\Controllers\AdminSantriController())->edit((int)$_GET['id']);
        break;
    case 'admin/santri/update':
        (new \App\Controllers\AdminSantriController())->update((int)$_GET['id']);
        break;
    case 'admin/santri/delete':
        (new \App\Controllers\AdminSantriController())->destroy((int)$_GET['id']);
        break;
    case 'admin/santri/show':
        (new \App\Controllers\AdminSantriController())->show((int)$_GET['id']);
        break;
    // Quran routes
    case 'quran':
        (new \App\Controllers\QuranController())->index();
        break;
    case 'quran/show':
        (new \App\Controllers\QuranController())->show((int)$_GET['id']);
        break;
    // Route untuk tafsir
    case 'quran/tafsir':
        $controller = new \App\Controllers\QuranController();
        $controller->tafsir((int)$_GET['surah'], (int)$_GET['ayat']);
        break;
    case 'tajweed-guide':
        (new \App\Controllers\TajweedGuideController())->index();
        break;
    // Bookmark
    case 'bookmark':
        (new \App\Controllers\BookmarkController())->index();
        break;
    case 'bookmark/add':
        (new \App\Controllers\BookmarkController())->add();
        break;
    case 'bookmark/remove':
        (new \App\Controllers\BookmarkController())->remove((int)$_GET['id']);
        break;
    // Statistics
    case 'statistics/weekly':
        (new \App\Controllers\StatisticsController())->weekly();
        break;
    case 'quran/log-play':
        (new \App\Controllers\QuranController())->logPlay();
        break;
}
