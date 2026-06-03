#!/usr/bin/env php
<?php
// Jalankan via command line: php cron/reminder.php
// Atau set cron job setiap hari jam 7 pagi: 0 7 * * * php /path/to/project/cron/reminder.php

require_once __DIR__ . '/../app/bootstrap.php';

use App\Models\TargetHafalan;
use App\Models\User;
use App\Models\Notifikasi;
use Carbon\Carbon;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$logger = new Logger('cron');
$logger->pushHandler(new StreamHandler(__DIR__ . '/../logs/cron.log', Logger::INFO));

$logger->info("Cron reminder dimulai");

// Ambil target yang deadline dalam 3 hari ke depan atau sudah lewat tapi belum dikirim reminder
// Untuk sederhana, kita cek target yang deadline <= 3 hari dari sekarang dan status reminder belum dikirim
// Kita tambahkan kolom 'reminder_sent' di tabel target_hafalan (jika perlu). Atau kita kirim setiap hari untuk deadline yang mendekat.
// Agar tidak berulang, kita akan kirim reminder untuk target dengan deadline antara hari ini sampai 3 hari ke depan, dan hanya sekali per target.
// Karena tidak ada kolom reminder_sent, kita gunakan logika: kirim jika deadline <= now+3 days dan deadline > now (belum lewat) atau lewat? Sesuai kebutuhan.

$today = Carbon::today();
$threeDaysLater = $today->copy()->addDays(3);

$targets = TargetHafalan::with('santri.ustadz', 'santri.orangTua')
    ->whereBetween('deadline', [$today, $threeDaysLater])
    ->get();

foreach ($targets as $target) {
    $santri = $target->santri;
    $deadline = Carbon::parse($target->deadline);
    $daysLeft = $today->diffInDays($deadline, false);
    if ($daysLeft < 0) {
        $message = "Target hafalan {$target->target_ayat} untuk santri {$santri->nama} telah melewati deadline pada {$target->deadline}. Segera evaluasi.";
    } else {
        $message = "Pengingat: Target hafalan {$target->target_ayat} untuk santri {$santri->nama} akan jatuh tempo dalam {$daysLeft} hari (deadline: {$target->deadline}).";
    }

    // Kirim notifikasi ke ustadz
    $ustadz = $santri->ustadz;
    if ($ustadz) {
        Notifikasi::create([
            'user_id' => $ustadz->id,
            'pesan' => $message,
            'is_read' => false
        ]);
        // Email ke ustadz (opsional)
        sendReminderEmail($ustadz->email, $ustadz->name, $santri->nama, $message);
    }

    // Kirim notifikasi ke semua orang tua
    foreach ($santri->orangTua as $ortu) {
        Notifikasi::create([
            'user_id' => $ortu->id,
            'pesan' => $message,
            'is_read' => false
        ]);
        sendReminderEmail($ortu->email, $ortu->name, $santri->nama, $message);
    }

    $logger->info("Reminder dikirim untuk target ID {$target->id}");
}

$logger->info("Cron reminder selesai");

function sendReminderEmail(string $to, string $name, string $santriName, string $message): void {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host       = $_ENV['MAIL_HOST'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $_ENV['MAIL_USERNAME'];
        $mail->Password   = $_ENV['MAIL_PASSWORD'];
        $mail->SMTPSecure = $_ENV['MAIL_ENCRYPTION'];
        $mail->Port       = $_ENV['MAIL_PORT'];
        $mail->setFrom($_ENV['MAIL_FROM_ADDRESS'], $_ENV['MAIL_FROM_NAME']);
        $mail->addAddress($to, $name);
        $mail->isHTML(true);
        $mail->Subject = "Pengingat Target Hafalan";
        $mail->Body    = "Assalamu'alaikum {$name},<br><br>{$message}<br><br>Silakan cek aplikasi Hafalan Tracker.<br>Jazakallah.";
        $mail->send();
    } catch (Exception $e) {
        // log error via Monolog bisa ditambahkan
    }
}