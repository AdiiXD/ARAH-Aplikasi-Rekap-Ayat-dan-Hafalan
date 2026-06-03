<?php

namespace App\Controllers;

use App\Models\Setting;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;

class AdminSettingController
{
    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('admin');
    }
    
    public function index()
    {
        $globalSettings = [
            'app_name' => Setting::get('app_name', 'Hafalan Tracker', null),
            'app_timezone' => Setting::get('app_timezone', 'Asia/Jakarta', null),
            'date_format' => Setting::get('date_format', 'd-m-Y', null),
            'notif_email_enabled' => Setting::get('notif_email_enabled', true, null),
            'reminder_days_before' => Setting::get('reminder_days_before', 3, null),
            'default_qari' => Setting::get('default_qari', 'ar.alafasy', null),
            'default_tajwid' => Setting::get('default_tajwid', false, null),
            'default_translation' => Setting::get('default_translation', true, null),
        ];
        
        $title = "Pengaturan Sistem";
        $activeMenu = "settings";
        ob_start();
        include __DIR__ . '/../../views/admin/settings/index.php';
        $content = ob_get_clean();
        include __DIR__ . '/../../views/layouts/main.php';
    }
    
    public function update()
    {
        $keys = ['app_name', 'app_timezone', 'date_format', 'notif_email_enabled', 
                 'reminder_days_before', 'default_qari', 'default_tajwid', 'default_translation'];
        
        foreach ($keys as $key) {
            $value = $_POST[$key] ?? null;
            if ($value !== null) {
                $type = in_array($key, ['notif_email_enabled', 'default_tajwid', 'default_translation']) ? 'boolean' : 'text';
                if ($type === 'boolean') {
                    $value = (bool) $value;
                }
                Setting::set($key, $value, null, $type);
            }
        }
        
        $_SESSION['success'] = "Pengaturan sistem berhasil disimpan.";
        header("Location: index.php?action=admin/settings");
        exit;
    }
}