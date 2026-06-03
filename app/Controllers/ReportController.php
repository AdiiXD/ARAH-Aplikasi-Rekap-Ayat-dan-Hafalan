<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Santri;
use App\Models\SetoranHafalan;
use App\Models\Kelas;
use App\Middleware\AuthMiddleware;
use Dompdf\Dompdf;
use Dompdf\Options;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class ReportController
{
    private string $role;  // Tambah type hint

    public function __construct()
    {
        AuthMiddleware::check();
        $this->role = $_SESSION['role'];
    }

    // Tampilkan halaman pilihan laporan sesuai role
    public function index(): void
    {
        switch ($this->role) {
            case 'admin':
                $kelasList = Kelas::all();
                include __DIR__ . '/../../views/reports/admin.php';
                break;
            case 'ustadz':
                $santriList = Santri::where('ustadz_id', $_SESSION['user_id'])->get();
                include __DIR__ . '/../../views/reports/ustadz.php';
                break;
            case 'orangtua':
                $orangtua = User::find($_SESSION['user_id']);
                $anakList = $orangtua->santrisAsOrangTua()->get();
                include __DIR__ . '/../../views/reports/orangtua.php';
                break;
            default:
                header('Location: index.php?action=dashboard');
                break;
        }
    }

    // ==================== ADMIN ====================

    public function adminExportExcel(): void
    {
        $kelasId = $_GET['kelas_id'] ?? null;
        $query = Santri::with(['kelas', 'ustadz']);
        if ($kelasId) {
            $query->where('kelas_id', $kelasId);
        }
        $santri = $query->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Santri');

        $headers = ['ID', 'Nama Santri', 'Kelas', 'Ustadz', 'Tanggal Lahir', 'Tahun Masuk'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4A1D2E');
            $sheet->getStyle($col . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $col++;
        }

        $row = 2;
        foreach ($santri as $s) {
            $sheet->setCellValue('A' . $row, $s->id);
            $sheet->setCellValue('B' . $row, $s->nama);
            $sheet->setCellValue('C' . $row, $s->kelas->nama_kelas ?? '-');
            $sheet->setCellValue('D' . $row, $s->ustadz->name ?? '-');
            $sheet->setCellValue('E' . $row, $s->tanggal_lahir);
            $sheet->setCellValue('F' . $row, $s->tahun_masuk);
            $row++;
        }

        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'laporan_santri_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    public function adminExportPdf(): void
    {
        $kelasList = Kelas::with('santris.ustadz')->get();
        
        $html = $this->renderPdfLayout('admin', [
            'kelasList' => $kelasList,
            'title' => 'Rekap Hafalan - Admin'
        ]);

        $this->outputPdf($html, 'rekap_admin.pdf');
    }

    // ==================== USTADZ ====================

    public function ustadzExportPdf(): void
    {
        $santriId = $_GET['santri_id'] ?? null;
        if (!$santriId) {
            $_SESSION['error'] = 'Pilih santri terlebih dahulu.';
            header('Location: index.php?action=reports');
            exit;
        }

        $santri = Santri::with(['kelas', 'ustadz', 'setoranHafalan', 'setoranMurajaah', 'targetHafalan'])
            ->where('ustadz_id', $_SESSION['user_id'])
            ->findOrFail($santriId);

        $html = $this->renderPdfLayout('ustadz', [
            'santri' => $santri,
            'title' => 'Laporan Hafalan Santri'
        ]);

        $this->outputPdf($html, 'laporan_hafalan_' . $santri->nama . '.pdf');
    }

    public function ustadzExportExcel(): void
    {
        $kelasId = $_GET['kelas_id'] ?? null;
        $santriIds = Santri::where('ustadz_id', $_SESSION['user_id'])
            ->when($kelasId, function($q) use ($kelasId) {
                return $q->where('kelas_id', $kelasId);
            })
            ->pluck('id');
        
        $setoran = SetoranHafalan::with('santri')
            ->whereIn('santri_id', $santriIds)
            ->orderBy('tgl_setor', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Setoran Hafalan');

        $headers = ['ID Setoran', 'Santri', 'Surat', 'Ayat Mulai', 'Ayat Selesai', 'Jumlah', 'Nilai', 'Tanggal Setor', 'Catatan'];
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('FF4A1D2E');
            $sheet->getStyle($col . '1')->getFont()->getColor()->setARGB('FFFFFFFF');
            $col++;
        }

        $row = 2;
        foreach ($setoran as $s) {
            $sheet->setCellValue('A' . $row, $s->id);
            $sheet->setCellValue('B' . $row, $s->santri->nama);
            $sheet->setCellValue('C' . $row, $s->surat);
            $sheet->setCellValue('D' . $row, $s->ayat_mulai);
            $sheet->setCellValue('E' . $row, $s->ayat_selesai);
            $sheet->setCellValue('F' . $row, $s->jumlah_ayat);
            $sheet->setCellValue('G' . $row, $s->nilai_quality);
            $sheet->setCellValue('H' . $row, $s->tgl_setor);
            $sheet->setCellValue('I' . $row, $s->catatan);
            $row++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'setoran_hafalan_' . date('Ymd_His') . '.xlsx';
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        $writer->save('php://output');
        exit;
    }

    // ==================== ORANG TUA ====================

    public function orangtuaExportPdf(): void
    {
        $santriId = $_GET['santri_id'] ?? null;
        if (!$santriId) {
            $_SESSION['error'] = 'Pilih anak terlebih dahulu.';
            header('Location: index.php?action=reports');
            exit;
        }

        $orangtua = User::find($_SESSION['user_id']);
        $santri = $orangtua->santrisAsOrangTua()
            ->with(['kelas', 'ustadz', 'setoranHafalan', 'setoranMurajaah', 'targetHafalan'])
            ->findOrFail($santriId);

        $html = $this->renderPdfLayout('orangtua', [
            'santri' => $santri,
            'title' => 'Laporan Progress Hafalan Anak'
        ]);

        $this->outputPdf($html, 'progress_' . $santri->nama . '.pdf');
    }

    // ==================== FUNGSI BANTUAN ====================

    /**
     * @param string $role admin|ustadz|orangtua
     * @param array<string,mixed> $data
     * @return string
     */
    private function renderPdfLayout(string $role, array $data): string
    {
        extract($data);
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="UTF-8">
            <title><?= $title ?></title>
            <style>
                body { font-family: 'DejaVu Sans', 'Arial', sans-serif; background: white; color: #2C2C2C; padding: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4A1D2E; padding-bottom: 10px; }
                .header h1 { color: #4A1D2E; margin: 0; }
                .header p { color: #6B6B6B; margin: 5px 0; }
                table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #4A1D2E; color: white; }
                .footer { text-align: center; font-size: 10px; color: #999; margin-top: 30px; }
                .badge-A { color: green; }
                .badge-B { color: orange; }
                .badge-C { color: #fd7e14; }
                .badge-D { color: red; }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>Hafalan Tracker</h1>
                <p><?= $title ?> - Dicetak: <?= date('d-m-Y H:i:s') ?></p>
            </div>

            <?php if ($role == 'admin'): ?>
                <h3>Rekapitulasi Hafalan per Kelas</h3>
                <?php foreach ($kelasList as $kelas): ?>
                <h4>Kelas: <?= htmlspecialchars($kelas->nama_kelas) ?></h4>
                <table>
                    <thead><tr><th>Nama Santri</th><th>Ustadz</th><th>Jml Setoran</th><th>Total Ayat</th><th>Rata-rata Nilai</th></tr></thead>
                    <tbody>
                        <?php foreach ($kelas->santris as $santri): ?>
                        <tr>
                            <td><?= htmlspecialchars($santri->nama) ?></td>
                            <td><?= htmlspecialchars($santri->ustadz->name ?? '-') ?></td>
                            <td><?= $santri->setoranHafalan->count() ?></td>
                            <td><?= $santri->setoranHafalan->sum('jumlah_ayat') ?></td>
                            <td><?= $santri->setoranHafalan->avg('nilai_quality') ?: '-' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php endforeach; ?>
            <?php elseif ($role == 'ustadz'): ?>
                <h3>Laporan Hafalan Santri: <?= htmlspecialchars($santri->nama) ?></h3>
                <p><strong>Kelas:</strong> <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></p>
                <p><strong>Ustadz:</strong> <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></p>
                <h4>Target Hafalan</h4>
                <table><thead><tr><th>Target</th><th>Deadline</th></tr></thead>
                    <tbody><?php foreach ($santri->targetHafalan as $target): ?>
                        <tr><td><?= htmlspecialchars($target->target_ayat) ?></td><td><?= $target->deadline ?></td></tr>
                    <?php endforeach; ?></tbody>
                </table>
                <h4>Riwayat Setoran Hafalan</h4>
                <table><thead><tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah</th><th>Nilai</th><th>Catatan</th></tr></thead>
                    <tbody><?php foreach ($santri->setoranHafalan as $s): ?>
                        <tr>
                            <td><?= $s->tgl_setor ?></td>
                            <td><?= htmlspecialchars($s->surat) ?></td>
                            <td><?= $s->ayat_mulai ?>-<?= $s->ayat_selesai ?></td>
                            <td><?= $s->jumlah_ayat ?></td>
                            <td class="badge-<?= $s->nilai_quality ?>"><?= $s->nilai_quality ?></td>
                            <td><?= htmlspecialchars($s->catatan) ?></td>
                        </tr>
                    <?php endforeach; ?></tbody>
                </table>
            <?php elseif ($role == 'orangtua'): ?>
                <h3>Progress Hafalan Anak: <?= htmlspecialchars($santri->nama) ?></h3>
                <p><strong>Kelas:</strong> <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></p>
                <p><strong>Ustadz:</strong> <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></p>
                <h4>Target Hafalan</h4>
                <table>...</table>
                <h4>Riwayat Setoran Hafalan</h4>
                <table>...</table>
            <?php endif; ?>

            <div class="footer">Hafalan Tracker - <?= date('Y') ?></div>
        </body>
        </html>
        <?php
        return ob_get_clean();
    }

    /**
     * @param string $html
     * @param string $filename
     * @return void
     */
    private function outputPdf(string $html, string $filename): void
    {
        $options = new Options();
        $options->set('defaultFont', 'DejaVu Sans');
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream($filename, ['Attachment' => true]);
        exit;
    }
}