<?php
/**
 * Migration Runner - Menjalankan semua migration di folder app/Database/Migrations/
 * 
 * Cara akses: http://localhost/hafalan-tracker/public/migrate_all.php
 * 
 * Untuk rollback (hapus semua tabel): http://localhost/hafalan-tracker/public/migrate_all.php?action=rollback
 */

require_once __DIR__ . '/../app/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Konfigurasi
$migrationPath = __DIR__ . '/../app/Database/Migrations/';
$action = $_GET['action'] ?? 'migrate'; // migrate atau rollback

// Ambil semua file migration dengan pola [0-9][0-9][0-9]_*.php
$files = glob($migrationPath . '[0-9][0-9][0-9]_*.php');
if (empty($files)) {
    die("❌ Tidak ada file migration ditemukan di folder $migrationPath\n");
}
natsort($files);
$migrationFiles = array_values($files);

// Fungsi konversi snake_case ke PascalCase
function snakeToPascal(string $snake): string
{
    return str_replace('_', '', ucwords($snake, '_'));
}

echo "========================================\n";
echo "      MIGRATION RUNNER\n";
echo "========================================\n";
echo "Aksi: " . strtoupper($action) . "\n\n";

// Nonaktifkan foreign key checks sementara
Capsule::statement('SET FOREIGN_KEY_CHECKS=0');

$total = count($migrationFiles);
$success = 0;
$failed = 0;

foreach ($migrationFiles as $index => $filePath) {
    $fileName = basename($filePath);
    $number = str_pad($index + 1, 3, '0', STR_PAD_LEFT);

    echo "[$number/$total] Memproses: $fileName ... ";

    require_once $filePath;

    $baseName = preg_replace('/^\d+_/', '', pathinfo($fileName, PATHINFO_FILENAME));
    $className = snakeToPascal($baseName);

    // Cek apakah class ditemukan, jika tidak coba alternatif nama (fallback)
    if (!class_exists($className)) {
        $alternatives = [
            'AddProfilePictureToUserTable',
            'AddProfilePictureToUsertable',
            'AddProfilePictureToUsersTable',
            'AddAvatarToUserTable',
            'CreateActivityLogsTable'
        ];
        $found = false;
        foreach ($alternatives as $alt) {
            if (class_exists($alt)) {
                $className = $alt;
                $found = true;
                break;
            }
        }
        if (!$found) {
            echo "❌ FAILED (class '$className' tidak ditemukan)\n";
            $failed++;
            continue;
        }
    }

    $migrator = new $className();

    try {
        if ($action === 'rollback') {
            if (method_exists($migrator, 'down')) {
                $migrator->down();
                echo "✅ ROLLBACK berhasil\n";
            } else {
                echo "⚠️ SKIP (method down() tidak ada)\n";
            }
        } else {
            if (method_exists($migrator, 'up')) {
                $migrator->up();
                echo "✅ SUCCESS\n";
            } else {
                echo "⚠️ SKIP (method up() tidak ada)\n";
            }
        }
        $success++;
    } catch (Exception $e) {
        echo "❌ FAILED - Error: " . $e->getMessage() . "\n";
        $failed++;
    }
}

// Aktifkan kembali foreign key checks
Capsule::statement('SET FOREIGN_KEY_CHECKS=1');

echo "\n========================================\n";
echo "HASIL: Sukses = $success, Gagal = $failed, Total = $total\n";
echo "========================================\n";

if ($failed > 0) {
    echo "⚠️ Ada migration yang gagal. Periksa pesan di atas.\n";
    exit(1);
} else {
    echo "✅ Semua migration berhasil dijalankan.\n";
    exit(0);
}