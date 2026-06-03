<?php

namespace App\Middleware;

class RoleMiddleware
{
    /**
     * @param string|array $role
     */
    public static function require($role): void
    {
        if (session_status() === PHP_SESSION_NONE) session_start();
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }
        
        $userRole = $_SESSION['role'];
        
        if (is_array($role)) {
            if (!in_array($userRole, $role)) {
                self::redirectToDashboard($userRole);
            }
        } else {
            if ($userRole !== $role) {
                self::redirectToDashboard($userRole);
            }
        }
    }
    
    private static function redirectToDashboard(string $role): void
    {
        switch ($role) {
            case 'admin': header('Location: index.php?action=admin/dashboard'); break;
            case 'ustadz': header('Location: index.php?action=ustadz/dashboard'); break;
            case 'orangtua': header('Location: index.php?action=orangtua/dashboard'); break;
            default: header('Location: index.php?action=login');
        }
        exit;
    }
}