<?php
/**
 * Migration Runner - Menjalankan migration satu per satu dengan status
 * 
 * Usage: 
 * - Browser: http://localhost/hafalan-tracker/public/migrate_all.php
 * - Terminal: php public/migrate_all.php
 */

require_once __DIR__ . '/../app/bootstrap.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// Daftar migration files sesuai urutan (001 sampai 012)
$migrations = [
    '001_create_users_table.php',
    '002_create_kelas_table.php',
    '003_create_santri_table.php',
    '004_create_orangtua_santri_table.php',
    '005_create_target_hafalan_table.php',
    '006_create_setoran_hafalan_table.php',
    '007_create_setoran_murajaah_table.php',
    '008_create_notifikasi_table.php',
    '009_create_logs_table.php',
    '010_create_bookmarks_table.php',
    '011_create_quran_quotes_table.php',
    '012_add_surah_number_to_quran_quotes.php'
];

$migrationPath = __DIR__ . '/../app/Database/Migrations/';

echo "========================================\n";
echo "      MIGRATION RUNNER\n";
echo "========================================\n\n";

// Matikan foreign key checks sementara
Capsule::statement('SET FOREIGN_KEY_CHECKS=0');

$total = count($migrations);
$success = 0;
$failed = 0;

foreach ($migrations as $index => $file) {
    $filePath = $migrationPath . $file;
    $number = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
    
    echo "[$number/$total] Memproses: $file ... ";
    
    if (!file_exists($filePath)) {
        echo "❌ FAILED (file tidak ditemukan)\n";
        $failed++;
        continue;
    }
    
    require_once $filePath;
    
    // Nama class dari nama file (contoh: 001_create_users_table.php -> CreateUsersTable)
    $className = preg_replace('/^\d+_/', '', pathinfo($file, PATHINFO_FILENAME));
    $className = str_replace('_', '', ucwords($className, '_'));
    
    if (!class_exists($className)) {
        echo "❌ FAILED (class '$className' tidak ditemukan)\n";
        $failed++;
        continue;
    }
    
    $migrator = new $className();
    
    try {
        // Cek aksi dari query string (opsional, untuk rollback)
        $action = $_GET['action'] ?? 'migrate';
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
    echo "⚠️ Ada migration yang gagal, periksa pesan error di atas.\n";
    exit(1);
} else {
    echo "✅ Semua migration berhasil dijalankan.\n";
    exit(0);
}