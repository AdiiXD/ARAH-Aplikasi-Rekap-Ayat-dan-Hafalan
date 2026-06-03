<?php

namespace App\Controllers;

use App\Helpers\QuranHelper;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class QuranController
{
    private QuranHelper $quranHelper;

    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require(['ustadz', 'orangtua']);
        $this->quranHelper = new QuranHelper();
    }

    public function index()
    {
        try {
            // Ambil parameter filter dari GET
            $search = trim($_GET['search'] ?? '');
            $revelation = $_GET['revelation'] ?? '';

            $chapters = $this->quranHelper->getChapters();

            // Filter berdasarkan tempat turun
            if ($revelation === 'makkah' || $revelation === 'madinah') {
                $chapters = array_filter($chapters, function ($chapter) use ($revelation) {
                    return ($chapter['revelation_place'] ?? '') === $revelation;
                });
            }

            // Filter berdasarkan pencarian (nama surat atau nomor surat)
            if (!empty($search)) {
                if (is_numeric($search)) {
                    // Cari berdasarkan nomor surat
                    $chapters = array_filter($chapters, function ($chapter) use ($search) {
                        return $chapter['id'] == $search;
                    });
                } else {
                    $searchLower = strtolower($search);
                    $chapters = array_filter($chapters, function ($chapter) use ($searchLower) {
                        return (strpos(strtolower($chapter['name_simple'] ?? ''), $searchLower) !== false)
                            || (strpos(strtolower($chapter['name_arabic'] ?? ''), $searchLower) !== false)
                            || (strpos(strtolower($chapter['name_transliteration'] ?? ''), $searchLower) !== false);
                    });
                }
            }

            $title = "Daftar Surat Al-Quran";
            $activeMenu = "quran";
            $searchQuery = $search;
            $revelationFilter = $revelation;
            ob_start();
            include __DIR__ . '/../../views/quran/index.php';
            $content = ob_get_clean();
            include __DIR__ . '/../../views/layouts/main.php';
        } catch (\Exception $e) {
            $error = "Gagal mengambil data surat: " . $e->getMessage();
            $this->showError($error);
        }
    }
    public function show(int $chapterId)
    {
        try {
            // Proses pergantian qori' jika ada POST
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reciter'])) {
                $this->quranHelper->setSelectedReciter($_POST['reciter']);
                header("Location: index.php?action=quran/show&id=$chapterId");
                exit;
            }

            // Proses toggle tajwid
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_tajwid'])) {
                $_SESSION['show_tajwid'] = !($_SESSION['show_tajwid'] ?? false);
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            $chapterInfo = $this->quranHelper->getChapterInfo($chapterId);
            if (!$chapterInfo) {
                throw new \Exception("Surat tidak ditemukan.");
            }

            // Ambil data ayat dengan tajwid
            $verses = $this->quranHelper->getVersesWithTajweed($chapterId);
            $reciters = $this->quranHelper->getAvailableReciters();
            $selectedReciter = $this->quranHelper->getSelectedReciter();
            $chapters = $this->quranHelper->getChapters(); // tambahkan ini
            $showTajwid = $_SESSION['show_tajwid'] ?? false;

            $title = "Surat " . ($chapterInfo['name_simple'] ?? 'Al-Quran');
            $activeMenu = "quran";
            ob_start();
            include __DIR__ . '/../../views/quran/show.php';
            $content = ob_get_clean();
            include __DIR__ . '/../../views/layouts/main.php';
        } catch (\Exception $e) {
            $error = "Gagal mengambil data ayat: " . $e->getMessage();
            $this->showError($error);
        }
    }

    public function tafsir(int $surah, int $ayat): void
    {
        header('Content-Type: application/json');
        $tafsir = $this->quranHelper->getTafsir($surah, $ayat);
        if ($tafsir) {
            echo json_encode(['status' => 'success', 'data' => $tafsir]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Tafsir untuk ayat ini tidak tersedia.']);
        }
        exit;
    }

    private function showError(string $error)
    {
        $title = "Error";
        ob_start();
        include __DIR__ . '/../../views/quran/error.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function search()
    {
        $query = $_GET['q'] ?? '';
        $results = [];
        if (strlen($query) >= 3) {
            // Gunakan API quran.com search
            $url = "https://api.quran.com/api/v4/search?q=" . urlencode($query) . "&page=1&per_page=20";
            try {
                $response = $this->quranHelper->makeRequest($url);
                $results = $response['verses'] ?? [];
            } catch (\Exception $e) {
                error_log("Search error: " . $e->getMessage());
            }
        }
        $title = "Pencarian Ayat";
        $activeMenu = "quran_search";
        ob_start();
        include __DIR__ . '/../../views/quran/search.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }
}
