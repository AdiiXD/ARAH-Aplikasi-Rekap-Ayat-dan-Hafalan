<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

// Setup Eloquent ORM
$capsule = new Capsule;
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => $_ENV['DB_HOST'] ?? 'localhost',
    'database'  => $_ENV['DB_NAME'] ?? 'hafalan_tracker',
    'username'  => $_ENV['DB_USER'] ?? 'root',
    'password'  => $_ENV['DB_PASS'] ?? '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);

// Set event dispatcher (diperlukan untuk event model seperti saving, booted dll)
$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make capsule global dan boot Eloquent
$capsule->setAsGlobal();
$capsule->bootEloquent();

// Set timezone
date_default_timezone_set($_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');