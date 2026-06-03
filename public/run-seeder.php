<?php
/**
 * Run Seeder Manual
 * 
 * Menjalakan DatabaseSeeder untuk mengisi data awal
 */

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/Database/Seeders/DatabaseSeeder.php';

use Illuminate\Database\Capsule\Manager as Capsule;

echo "<pre>";
echo "Menjalankan Database Seeder...\n";

try {
    // Matikan foreign key checks
    Capsule::statement('SET FOREIGN_KEY_CHECKS=0');
    
    $seeder = new DatabaseSeeder();
    $seeder->run();
    
    Capsule::statement('SET FOREIGN_KEY_CHECKS=1');
    echo "\n✅ Seeder berhasil dijalankan.\n";
} catch (Exception $e) {
    echo "\n❌ Error: " . $e->getMessage() . "\n";
}

echo "</pre>";