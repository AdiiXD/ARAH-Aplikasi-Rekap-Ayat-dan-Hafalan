<?php

use App\Models\ActivityLog;

function logActivity(string $action, ?string $description = null): void
{
    if (session_status() === PHP_SESSION_NONE) session_start();
    $userId = $_SESSION['user_id'] ?? null;
    $role = $_SESSION['role'] ?? 'guest';
    $ip = $_SERVER['REMOTE_ADDR'] ?? null;
    ActivityLog::create([
        'user_id' => $userId,
        'role' => $role,
        'action' => $action,
        'description' => $description,
        'ip_address' => $ip,
    ]);
}