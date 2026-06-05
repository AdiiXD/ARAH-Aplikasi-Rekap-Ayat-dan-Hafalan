<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
use App\Models\SetoranHafalan;

$title = "Progress Hafalan - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();

// Ambil data untuk grafik garis (30 hari terakhir)
$endDate = Carbon::now();
$startDate = $endDate->copy()->subDays(29);
$dailySetoran = SetoranHafalan::where('santri_id', $santri->id)
    ->whereBetween('tgl_setor', [$startDate, $endDate])
    ->selectRaw('DATE(tgl_setor) as tanggal, SUM(jumlah_ayat) as total_ayat')
    ->groupBy('tanggal')
    ->orderBy('tanggal', 'asc')
    ->get()
    ->keyBy('tanggal');

$labels = [];
$dataPoints = [];
$period = new \DatePeriod($startDate, new \DateInterval('P1D'), $endDate->addDay());
foreach ($period as $date) {
    $dateStr = $date->format('Y-m-d');
    $labels[] = $date->format('d M');
    $dataPoints[] = $dailySetoran->has($dateStr) ? $dailySetoran[$dateStr]->total_ayat : 0;
}
$chartLabelsJson = json_encode($labels);
$chartDataJson = json_encode($dataPoints);
?>

<div class="container px-0">
    <!-- Header Profil Santri dengan card modern -->
    <div class="card-custom p-4 mb-4" style="border-radius: 28px; background: linear-gradient(135deg, #FFF9EF 0%, #FDF8F0 100%);">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3 class="mb-2"><?= htmlspecialchars($santri->nama) ?></h3>
                <div class="d-flex flex-wrap gap-2">
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-person-badge"></i> NIS: <?= htmlspecialchars($santri->nis ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-person"></i> Panggilan: <?= htmlspecialchars($santri->nickname ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-building"></i> Kelas: <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-person-badge"></i> Ustadz: <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></span>
                </div>
            </div>
            <a href="index.php?action=orangtua/dashboard" class="btn btn-outline-secondary rounded-pill mt-2 mt-sm-0">
                <i class="bi bi-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    <!-- Target Hafalan -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-bullseye fs-4 text-maroon"></i>
            <h5 class="mb-0">Target Hafalan</h5>
        </div>
        <?php if ($santri->targetHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-4">
                <i class="bi bi-flag fs-2 text-muted"></i>
                <p class="mt-2 mb-0">Belum ada target yang ditetapkan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light">
                        <tr><th>Target</th><th>Deadline</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($santri->targetHafalan as $target): ?>
                        <tr>
                            <td><?= htmlspecialchars($target->target_ayat) ?></td>
                            <td><?= Carbon::parse($target->deadline)->format('d M Y') ?></td>
                            <td><?= $target->deadline < Carbon::now() ? '<span class="badge bg-danger rounded-pill">Lewat</span>' : '<span class="badge bg-success rounded-pill">Aktif</span>' ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat Setoran Hafalan -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-list-check fs-4 text-maroon"></i>
            <h5 class="mb-0">Riwayat Setoran Hafalan</h5>
        </div>
        <?php if ($santri->setoranHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-4">
                <i class="bi bi-journal-bookmark-fill fs-2 text-muted"></i>
                <p class="mt-2 mb-0">Belum ada setoran hafalan.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah</th><th>Nilai</th><th>Catatan</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($santri->setoranHafalan as $setoran): ?>
                        <tr>
                            <td><?= Carbon::parse($setoran->tgl_setor)->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($setoran->surat) ?></td>
                            <td><?= $setoran->ayat_mulai ?> - <?= $setoran->ayat_selesai ?></td>
                            <td><?= $setoran->jumlah_ayat ?> ayat</span></td>
                            <td><span class="badge rounded-pill bg-<?= $setoran->nilai_quality == 'A' ? 'success' : ($setoran->nilai_quality == 'B' ? 'warning' : ($setoran->nilai_quality == 'C' ? 'info' : 'danger')) ?>"><?= $setoran->nilai_quality ?></span></td>
                            <td><?= htmlspecialchars($setoran->catatan) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat Murajaah -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-arrow-repeat fs-4 text-maroon"></i>
            <h5 class="mb-0">Riwayat Murajaah</h5>
        </div>
        <?php if ($santri->setoranMurajaah->isEmpty()): ?>
            <div class="alert alert-light text-center py-4">
                <i class="bi bi-arrow-repeat fs-2 text-muted"></i>
                <p class="mt-2 mb-0">Belum ada setoran murajaah.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light">
                        <tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah Ulangan</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($santri->setoranMurajaah as $m): ?>
                        <tr>
                            <td><?= Carbon::parse($m->tgl_murajaah)->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($m->surat) ?></td>
                            <td><?= $m->ayat ?></td>
                            <td><?= $m->jumlah_ulangan ?> kali</span></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Grafik Perkembangan -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex align-items-center gap-2 mb-3">
            <i class="bi bi-graph-up fs-4 text-maroon"></i>
            <h5 class="mb-0">Grafik Perkembangan Hafalan (30 Hari Terakhir)</h5>
        </div>
        <canvas id="dailyHafalanChart" style="width:100%; height:300px"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('dailyHafalanChart'), {
        type: 'line',
        data: {
            labels: <?= $chartLabelsJson ?>,
            datasets: [{
                label: 'Jumlah Ayat',
                data: <?= $chartDataJson ?>,
                borderColor: '#4A1D2E',
                backgroundColor: 'rgba(74, 29, 46, 0.05)',
                borderWidth: 2,
                fill: true,
                tension: 0.3,
                pointBackgroundColor: '#4A1D2E',
                pointBorderColor: '#fff',
                pointRadius: 4,
                pointHoverRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: { position: 'top' },
                tooltip: { mode: 'index', intersect: false }
            },
            scales: {
                y: { beginAtZero: true, title: { display: true, text: 'Jumlah Ayat' } },
                x: { ticks: { maxRotation: 45, minRotation: 45 } }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>