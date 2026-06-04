<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
use App\Models\SetoranHafalan;

$title = "Detail Santri: " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();

// Data untuk grafik garis (30 hari terakhir)
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

<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between">
        <h3><?= htmlspecialchars($santri->nama) ?></h3>
        <div>
            <a href="index.php?action=admin/santri" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali ke Daftar</a>
        </div>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6"><strong>NIS:</strong> <?= htmlspecialchars($santri->nis ?? '-') ?></div>
        <div class="col-md-6"><strong>Nama Panggilan:</strong> <?= htmlspecialchars($santri->nickname ?? '-') ?></div>
        <div class="col-md-6"><strong>Kelas:</strong> <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></div>
        <div class="col-md-6"><strong>Ustadz:</strong> <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></div>
        <div class="col-md-6"><strong>Tahun Masuk:</strong> <?= $santri->tahun_masuk ?></div>
        <div class="col-md-6"><strong>Tanggal Lahir:</strong> <?= Carbon::parse($santri->tanggal_lahir)->format('d M Y') ?></div>
    </div>
</div>

<!-- Target Hafalan (read-only) -->
<div class="card-custom p-4 mb-4">
    <h5><i class="bi bi-bullseye"></i> Target Hafalan</h5>
    <?php if ($santri->targetHafalan->isEmpty()): ?>
        <p class="text-muted">Belum ada target yang ditetapkan.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr><th>Target</th><th>Deadline</th><th>Status</th></tr>
                </thead>
                <tbody>
                <?php foreach ($santri->targetHafalan as $target): ?>
                    <tr>
                        <td><?= htmlspecialchars($target->target_ayat) ?></td>
                        <td><?= Carbon::parse($target->deadline)->format('d M Y') ?></td>
                        <td><?= $target->deadline < Carbon::now() ? '<span class="badge bg-danger">Lewat</span>' : '<span class="badge bg-success">Aktif</span>' ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Riwayat Setoran Hafalan (tanpa tombol aksi) -->
<div class="card-custom p-4 mb-4">
    <h5><i class="bi bi-list-check"></i> Riwayat Setoran Hafalan</h5>
    <?php if ($santri->setoranHafalan->isEmpty()): ?>
        <p class="text-muted">Belum ada setoran hafalan.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah</th><th>Nilai</th><th>Catatan</th></tr>
                </thead>
                <tbody>
                <?php foreach ($santri->setoranHafalan as $setoran): ?>
                    <tr>
                        <td><?= Carbon::parse($setoran->tgl_setor)->format('d/m/Y') ?></td>
                        <td><?= htmlspecialchars($setoran->surat) ?></td>
                        <td><?= $setoran->ayat_mulai ?> - <?= $setoran->ayat_selesai ?></td>
                        <td><?= $setoran->jumlah_ayat ?> ayat</td>
                        <td><span class="badge bg-<?= $setoran->nilai_quality == 'A' ? 'success' : ($setoran->nilai_quality == 'B' ? 'warning' : ($setoran->nilai_quality == 'C' ? 'info' : 'danger')) ?>"><?= $setoran->nilai_quality ?></span></td>
                        <td><?= htmlspecialchars($setoran->catatan) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Riwayat Murajaah (tanpa tombol aksi) -->
<div class="card-custom p-4 mb-4">
    <h5><i class="bi bi-arrow-repeat"></i> Riwayat Murajaah</h5>
    <?php if ($santri->setoranMurajaah->isEmpty()): ?>
        <p class="text-muted">Belum ada setoran murajaah.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah Ulangan</th></tr>
                </thead>
                <tbody>
                <?php foreach ($santri->setoranMurajaah as $m): ?>
                    <tr>
                        <td><?= Carbon::parse($m->tgl_murajaah)->format('d/m/Y') ?></td>
                        <td><?= htmlspecialchars($m->surat) ?></td>
                        <td><?= $m->ayat ?></td>
                        <td><?= $m->jumlah_ulangan ?> kali</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>

<!-- Grafik Perkembangan Hafalan Harian -->
<div class="card-custom p-4">
    <h5><i class="bi bi-graph-up"></i> Perkembangan Hafalan Harian (30 Hari Terakhir)</h5>
    <canvas id="dailyHafalanChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('dailyHafalanChart').getContext('2d');
    new Chart(ctx, {
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
                pointRadius: 3,
                pointHoverRadius: 5
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
                y: { beginAtZero: true, title: { display: true, text: 'Jumlah Ayat' }, grid: { color: '#E6DDD0' } },
                x: { title: { display: true, text: 'Tanggal' }, ticks: { maxRotation: 45, minRotation: 45 } }
            }
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';