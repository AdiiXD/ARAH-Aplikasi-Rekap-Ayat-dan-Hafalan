<?php

namespace App\Controllers;

use App\Models\Bookmark;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class BookmarkController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require(['ustadz', 'orangtua']);
    }

    public function index()
    {
        $bookmarks = Bookmark::where('user_id', $_SESSION['user_id'])
            ->orderBy('created_at', 'desc')
            ->get();
        $title = "Bookmark Ayat";
        $activeMenu = "bookmark";
        ob_start();
        include __DIR__ . '/../../views/bookmark/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }

    public function add()
    {
        $surah = (int)$_POST['surah'];
        $ayat = (int)$_POST['ayat'];
        $surahName = $_POST['surah_name'] ?? '';

        $exists = Bookmark::where('user_id', $_SESSION['user_id'])
            ->where('surah', $surah)
            ->where('ayat', $ayat)
            ->exists();

        if (!$exists) {
            Bookmark::create([
                'user_id' => $_SESSION['user_id'],
                'surah' => $surah,
                'ayat' => $ayat,
                'surah_name' => $surahName
            ]);
            $_SESSION['success'] = "Ayat disimpan ke bookmark.";
        } else {
            $_SESSION['error'] = "Ayat sudah ada di bookmark.";
        }

        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function remove(int $id)
    {
        $bookmark = Bookmark::where('user_id', $_SESSION['user_id'])->where('id', $id)->first();
        if ($bookmark) {
            $bookmark->delete();
            $_SESSION['success'] = "Bookmark dihapus.";
        }
        header("Location: index.php?action=bookmark");
        exit;
    }
}
