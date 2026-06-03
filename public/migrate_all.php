<?php
/**
 * Migration Runner - Menjalankan semua migration satu per satu
 * 
 * Cara akses:
 * - Browser   : http://localhost/hafalan-tracker/public/migrate_all.php
 * - Terminal  : php public/migrate_all.php
 * 
 * Rollback: ?action=rollback
 */

require_once __DIR__ . '/../app/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$migrationPath = __DIR__ . '/../app/Database/Migrations/';
$action = $_GET['action'] ?? 'migrate';

$files = glob($migrationPath . '[0-9][0-9][0-9]_*.php');
if (empty($files)) {
    die("❌ Tidak ada file migration ditemukan di folder $migrationPath\n");
}
natsort($files);
$migrationFiles = array_values($files);

function snakeToPascal(string $snake): string
{
    return str_replace('_', '', ucwords($snake, '_'));
}

echo "========================================\n";
echo "      MIGRATION RUNNER\n";
echo "========================================\n";
echo "Aksi: " . strtoupper($action) . "\n\n";

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

    if (!class_exists($className)) {
        $alternatives = [
            'AddProfilePictureToUserTable',
            'AddProfilePictureToUsertable',
            'AddProfilePictureToUsersTable',
            'AddAvatarToUserTable'
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