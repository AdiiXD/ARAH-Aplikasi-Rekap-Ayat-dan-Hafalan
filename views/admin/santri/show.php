<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
use App\Models\SetoranHafalan;

$title = "Detail Santri - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();

// Data grafik (30 hari)
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
    $labels[] = $date->format('d M');
    $dataPoints[] = $dailySetoran->has($date->format('Y-m-d')) ? $dailySetoran[$date->format('Y-m-d')]->total_ayat : 0;
}
?>
<div class="container px-0">
    <div class="card-custom p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3><?= htmlspecialchars($santri->nama) ?></h3>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    <span class="badge bg-light text-dark rounded-pill">NIS: <?= htmlspecialchars($santri->nis ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill">Panggilan: <?= htmlspecialchars($santri->nickname ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill">Kelas: <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill">Ustadz: <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></span>
                </div>
            </div>
            <a href="index.php?action=admin/santri" class="btn btn-secondary rounded-pill btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
        </div>
    </div>

    <!-- Target Hafalan -->
    <div class="card-custom p-4 mb-4">
        <h5><i class="bi bi-bullseye"></i> Target Hafalan</h5>
        <?php if ($santri->targetHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-3">Belum ada target.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light"><tr><th>Target</th><th>Deadline</th><th>Status</th></tr></thead>
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

    <!-- Riwayat Setoran Hafalan (read-only) -->
    <div class="card-custom p-4 mb-4">
        <h5><i class="bi bi-list-check"></i> Riwayat Setoran Hafalan</h5>
        <?php if ($santri->setoranHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-3">Belum ada setoran.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah</th><th>Nilai</th><th>Catatan</th></tr></thead>
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
        <h5><i class="bi bi-arrow-repeat"></i> Riwayat Murajaah</h5>
        <?php if ($santri->setoranMurajaah->isEmpty()): ?>
            <div class="alert alert-light text-center py-3">Belum ada murajaah.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah Ulangan</th></tr></thead>
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

    <!-- Grafik -->
    <div class="card-custom p-4">
        <h5><i class="bi bi-graph-up"></i> Perkembangan Hafalan (30 Hari Terakhir)</h5>
        <canvas id="dailyChart" style="width:100%; height:300px"></canvas>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('dailyChart'), {
        type: 'line',
        data: { labels: <?= json_encode($labels) ?>, datasets: [{ label: 'Jumlah Ayat', data: <?= json_encode($dataPoints) ?>, borderColor: '#4A1D2E', backgroundColor: 'rgba(74,29,46,0.05)', fill: true, tension: 0.3 }] },
        options: { responsive: true }
    });
</script>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>