<?php

namespace App\Controllers;

use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use App\Models\QuranQuote;

class AdminDashboardController
{
    public function index()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
        $name = $_SESSION['name'];
        $dailyQuote = QuranQuote::getDailyQuote();
        include __DIR__ . '/../../views/admin/dashboard.php';
    }
}
