<?php

namespace App\Controllers;

use App\Helpers\QuranHelper;
use App\Models\Setting;
use App\Models\Bookmark;
use App\Models\QuranListeningLog;
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

    /**
     * Halaman daftar surat dengan filter pencarian (nama surah, nomor, tempat turun)
     */
    public function index()
    {
        try {
            $search = trim($_GET['search'] ?? '');
            $revelation = $_GET['revelation'] ?? '';
            $goto = $_GET['goto'] ?? '';

            // Jika ada navigasi langsung surah:ayat
            if (preg_match('/^(\d+):(\d+)$/', $goto, $matches)) {
                $surahId = (int)$matches[1];
                $ayat = (int)$matches[2];
                header("Location: index.php?action=quran/show&id={$surahId}#ayat-{$ayat}");
                exit;
            }

            $chapters = $this->quranHelper->getChapters();

            // Filter tempat turun
            if ($revelation === 'makkah' || $revelation === 'madinah') {
                $chapters = array_filter($chapters, function($chapter) use ($revelation) {
                    return ($chapter['revelation_place'] ?? '') === $revelation;
                });
            }

            // Pencarian nama atau nomor surat
            if (!empty($search)) {
                if (is_numeric($search)) {
                    $chapters = array_filter($chapters, function($chapter) use ($search) {
                        return $chapter['id'] == $search;
                    });
                } else {
                    $searchLower = strtolower($search);
                    $chapters = array_filter($chapters, function($chapter) use ($searchLower) {
                        return (strpos(strtolower($chapter['name_simple'] ?? ''), $searchLower) !== false)
                            || (strpos(strtolower($chapter['name_arabic'] ?? ''), $searchLower) !== false)
                            || (strpos(strtolower($chapter['name_transliteration'] ?? ''), $searchLower) !== false);
                    });
                }
            }

            $title = "Daftar Surat Al-Quran";
            $activeMenu = "quran";
            ob_start();
            include __DIR__ . '/../../views/quran/index.php';
            $content = ob_get_clean();
            include __DIR__ . '/../../views/layouts/main.php';
        } catch (\Exception $e) {
            $error = "Gagal mengambil data surat: " . $e->getMessage();
            $this->showError($error);
        }
    }

    /**
     * Halaman baca surat dengan audio, tajwid, terjemahan, dan preferensi user
     */
    public function show(int $chapterId)
    {
        try {
            $userId = $_SESSION['user_id'];

            // Proses ganti qari (jika POST dari floating panel)
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reciter'])) {
                Setting::set('default_qari', $_POST['reciter'], $userId, 'text');
                header("Location: index.php?action=quran/show&id={$chapterId}");
                exit;
            }

            // Proses toggle tajwid (jika POST)
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_tajwid'])) {
                $current = Setting::get('default_tajwid', false, $userId);
                Setting::set('default_tajwid', !$current, $userId, 'boolean');
                header("Location: " . $_SERVER['REQUEST_URI']);
                exit;
            }

            // Ambil preferensi user
            $selectedReciter = Setting::get('default_qari', 'ar.alafasy', $userId);
            $showTajwid = (bool) Setting::get('default_tajwid', false, $userId);
            $showTranslation = (bool) Setting::get('default_translation', true, $userId);
            // Untuk sementara, translation toggle masih via session? Bisa juga pakai setting.
            // Tapi di view ada tombol toggle translation sendiri (via session). Kita biarkan session dulu.

            // Data surat
            $chapterInfo = $this->quranHelper->getChapterInfo($chapterId);
            if (!$chapterInfo) {
                throw new \Exception("Surat tidak ditemukan.");
            }

            // Ambil ayat dengan tajwid
            $verses = $this->quranHelper->getVersesWithTajweed($chapterId);
            $reciters = $this->quranHelper->getAvailableReciters();

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

    /**
     * Endpoint untuk mengambil tafsir via AJAX
     */
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

    /**
     * Endpoint untuk mencatat pemutaran audio (statistik bacaan harian)
     */
    public function logPlay(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['status' => 'error', 'message' => 'Method not allowed']);
            exit;
        }

        $userId = $_SESSION['user_id'] ?? null;
        if (!$userId) {
            echo json_encode(['status' => 'error', 'message' => 'Not authenticated']);
            exit;
        }

        $surah = (int) ($_POST['surah'] ?? 0);
        $ayat = (int) ($_POST['ayat'] ?? 0);

        if ($surah <= 0 || $ayat <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid surah or ayat']);
            exit;
        }

        QuranListeningLog::create([
            'user_id' => $userId,
            'surah_number' => $surah,
            'ayat_number' => $ayat,
            'played_at' => date('Y-m-d H:i:s')
        ]);

        echo json_encode(['status' => 'success', 'message' => 'Logged']);
        exit;
    }

    /**
     * Halaman error
     */
    private function showError(string $error)
    {
        $title = "Error";
        ob_start();
        include __DIR__ . '/../../views/quran/error.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }
}