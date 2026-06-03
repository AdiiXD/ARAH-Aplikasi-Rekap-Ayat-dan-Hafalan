<?php

namespace App\Middleware;

class AuthMiddleware
{
    public static function check()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
    }

    public static function guest()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (isset($_SESSION['user_id'])) {
            $role = $_SESSION['role'];
            switch ($role) {
                case 'admin': header('Location: index.php?action=admin/dashboard'); break;
                case 'ustadz': header('Location: index.php?action=ustadz/dashboard'); break;
                case 'orangtua': header('Location: index.php?action=orangtua/dashboard'); break;
            }
            exit;
        }
    }
}