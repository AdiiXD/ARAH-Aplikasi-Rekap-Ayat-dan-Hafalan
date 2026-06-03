<?php
namespace App\Controllers;

use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\SetoranMurajaah;
use App\Models\Notifikasi;
use App\Middleware\AuthMiddleware;
use App\Middleware\RoleMiddleware;
use Carbon\Carbon;
use Monolog\Logger;              // ← PASTIKAN ADA
use Monolog\Handler\StreamHandler;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class UstadzSetoranController
{
    private Logger $logger;      // ← TAMBAHKAN TYPE HINT

    public function __construct()
    {
        AuthMiddleware::check();
        RoleMiddleware::require('ustadz');

        // Inisialisasi logger
        $this->logger = new Logger('ustadz');
        $this->logger->pushHandler(new StreamHandler(__DIR__ . '/../../logs/app.log', Logger::INFO));
    }

    // ==================== SETORAN HAFALAN ====================

    public function createHafalan(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);
        include __DIR__ . '/../../views/ustadz/setoran/create_hafalan.php';
    }

    public function storeHafalan(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);

        $setoran = new SetoranHafalan();
        $setoran->santri_id = $santriId;
        $setoran->surat = $_POST['surat'];
        $setoran->ayat_mulai = $_POST['ayat_mulai'];
        $setoran->ayat_selesai = $_POST['ayat_selesai'];
        $setoran->jumlah_ayat = $_POST['jumlah_ayat'];
        $setoran->nilai_quality = $_POST['nilai_quality'];
        $setoran->catatan = $_POST['catatan'] ?? null;
        $setoran->tgl_setor = $_POST['tgl_setor'] ?? Carbon::today();
        $setoran->save();

        // Kirim notifikasi ke orang tua
        foreach ($santri->orangTua as $ortu) {
            Notifikasi::create([
                'user_id' => $ortu->id,
                'pesan' => "Anak Anda {$santri->nama} telah menambah setoran hafalan: Surat {$setoran->surat} ayat {$setoran->ayat_mulai}-{$setoran->ayat_selesai}. Nilai: {$setoran->nilai_quality}",
                'is_read' => false
            ]);
            $this->sendEmailNotification($ortu->email, $ortu->name, $santri->nama, "setoran hafalan", "Surat {$setoran->surat} ayat {$setoran->ayat_mulai}-{$setoran->ayat_selesai}");
        }

        $this->logger->info("Setoran hafalan ditambahkan", ['santri_id' => $santriId, 'ustadz_id' => $_SESSION['user_id']]);

        $_SESSION['success'] = 'Setoran hafalan berhasil ditambahkan.';
        header("Location: index.php?action=ustadz/santri/show&id=$santriId");
        exit;
    }

    public function editHafalan(int $id)
    {
        $setoran = SetoranHafalan::with('santri')->findOrFail($id);
        if ($setoran->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        include __DIR__ . '/../../views/ustadz/setoran/edit_hafalan.php';
    }

    public function updateHafalan(int $id)
    {
        $setoran = SetoranHafalan::with('santri')->findOrFail($id);
        if ($setoran->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $setoran->surat = $_POST['surat'];
        $setoran->ayat_mulai = $_POST['ayat_mulai'];
        $setoran->ayat_selesai = $_POST['ayat_selesai'];
        $setoran->jumlah_ayat = $_POST['jumlah_ayat'];
        $setoran->nilai_quality = $_POST['nilai_quality'];
        $setoran->catatan = $_POST['catatan'] ?? null;
        $setoran->tgl_setor = $_POST['tgl_setor'];
        $setoran->save();

        $_SESSION['success'] = 'Setoran hafalan diperbarui.';
        header("Location: index.php?action=ustadz/santri/show&id=" . $setoran->santri_id);
        exit;
    }

    public function destroyHafalan(int $id)
    {
        $setoran = SetoranHafalan::with('santri')->findOrFail($id);
        if ($setoran->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $santriId = $setoran->santri_id;
        $setoran->delete();
        $_SESSION['success'] = 'Setoran hafalan dihapus.';
        header("Location: index.php?action=ustadz/santri/show&id=$santriId");
        exit;
    }

    // ==================== SETORAN MURAAJAH ====================

    public function createMurajaah(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);
        include __DIR__ . '/../../views/ustadz/setoran/create_murajaah.php';
    }

    public function storeMurajaah(int $santriId)
    {
        $santri = Santri::where('ustadz_id', $_SESSION['user_id'])->findOrFail($santriId);

        $murajaah = new SetoranMurajaah();
        $murajaah->santri_id = $santriId;
        $murajaah->surat = $_POST['surat'];
        $murajaah->ayat = $_POST['ayat'];
        $murajaah->jumlah_ulangan = $_POST['jumlah_ulangan'];
        $murajaah->tgl_murajaah = $_POST['tgl_murajaah'] ?? Carbon::today();
        $murajaah->save();

        // Notifikasi ke orang tua
        foreach ($santri->orangTua as $ortu) {
            Notifikasi::create([
                'user_id' => $ortu->id,
                'pesan' => "Anak Anda {$santri->nama} telah menambah setoran murajaah: Surat {$murajaah->surat} ayat {$murajaah->ayat} ({$murajaah->jumlah_ulangan} kali)",
                'is_read' => false
            ]);
            $this->sendEmailNotification($ortu->email, $ortu->name, $santri->nama, "murajaah", "Surat {$murajaah->surat} ayat {$murajaah->ayat} diulang {$murajaah->jumlah_ulangan} kali");
        }

        $this->logger->info("Setoran murajaah ditambahkan", ['santri_id' => $santriId, 'ustadz_id' => $_SESSION['user_id']]);

        $_SESSION['success'] = 'Setoran murajaah berhasil ditambahkan.';
        header("Location: index.php?action=ustadz/santri/show&id=$santriId");
        exit;
    }

    public function editMurajaah(int $id)
    {
        $murajaah = SetoranMurajaah::with('santri')->findOrFail($id);
        if ($murajaah->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        include __DIR__ . '/../../views/ustadz/setoran/edit_murajaah.php';
    }

    public function updateMurajaah(int $id)
    {
        $murajaah = SetoranMurajaah::with('santri')->findOrFail($id);
        if ($murajaah->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $murajaah->surat = $_POST['surat'];
        $murajaah->ayat = $_POST['ayat'];
        $murajaah->jumlah_ulangan = $_POST['jumlah_ulangan'];
        $murajaah->tgl_murajaah = $_POST['tgl_murajaah'];
        $murajaah->save();

        $_SESSION['success'] = 'Setoran murajaah diperbarui.';
        header("Location: index.php?action=ustadz/santri/show&id=" . $murajaah->santri_id);
        exit;
    }

    public function destroyMurajaah(int $id)
    {
        $murajaah = SetoranMurajaah::with('santri')->findOrFail($id);
        if ($murajaah->santri->ustadz_id != $_SESSION['user_id']) {
            $_SESSION['error'] = 'Akses ditolak.';
            header('Location: index.php?action=ustadz/dashboard');
            exit;
        }
        $santriId = $murajaah->santri_id;
        $murajaah->delete();
        $_SESSION['success'] = 'Setoran murajaah dihapus.';
        header("Location: index.php?action=ustadz/santri/show&id=$santriId");
        exit;
    }

    // ==================== Helper: Email Notification ====================
    private function sendEmailNotification(string $toEmail, string $toName, string $santriName, string $jenis, string $detail): void
    {
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
            $mail->addAddress($toEmail, $toName);
            $mail->isHTML(true);
            $mail->Subject = "Notifikasi Hafalan: {$santriName}";
            $mail->Body    = "Assalamu'alaikum {$toName},<br><br>Anak Anda <strong>{$santriName}</strong> telah menambah {$jenis}:<br>{$detail}<br><br>Silakan cek aplikasi Hafalan Tracker untuk detail lengkap.<br><br>Jazakallah.";
            $mail->send();
        } catch (Exception $e) {
            $this->logger->error("Email gagal dikirim ke {$toEmail}", ['error' => $mail->ErrorInfo]);
        }
    }
}