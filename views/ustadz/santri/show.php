<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
use App\Models\SetoranHafalan;

$title = "Progress Hafalan - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();

// Data grafik garis (30 hari)
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
$chartLabelsJson = json_encode($labels);
$chartDataJson = json_encode($dataPoints);
?>

<div class="container px-0">
    <!-- Header -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex flex-wrap justify-content-between align-items-center">
            <div>
                <h3><?= htmlspecialchars($santri->nama) ?></h3>
                <div class="d-flex flex-wrap gap-2 mt-1">
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-person-badge"></i> NIS: <?= htmlspecialchars($santri->nis ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-person"></i> Panggilan: <?= htmlspecialchars($santri->nickname ?? '-') ?></span>
                    <span class="badge bg-light text-dark rounded-pill"><i class="bi bi-building"></i> Kelas: <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></span>
                </div>
            </div>
            <div>
                <a href="index.php?action=ustadz/setoran/create_hafalan&id=<?= $santri->id ?>" class="btn btn-maroon btn-sm rounded-pill"><i class="bi bi-plus-lg"></i> Setoran Hafalan</a>
                <a href="index.php?action=ustadz/setoran/create_murajaah&id=<?= $santri->id ?>" class="btn btn-outline-maroon btn-sm rounded-pill"><i class="bi bi-arrow-repeat"></i> Murajaah</a>
                <a href="index.php?action=ustadz/dashboard" class="btn btn-secondary btn-sm rounded-pill"><i class="bi bi-arrow-left"></i> Kembali</a>
            </div>
        </div>
    </div>

    <!-- Target Hafalan -->
    <div class="card-custom p-4 mb-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5><i class="bi bi-bullseye"></i> Target Hafalan</h5>
            <a href="index.php?action=ustadz/target/create&id=<?= $santri->id ?>" class="btn btn-sm btn-outline-maroon rounded-pill">+ Tambah Target</a>
        </div>
        <?php if ($santri->targetHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-3">Belum ada target.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead class="table-light"><tr><th>Target</th><th>Deadline</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($santri->targetHafalan as $target): ?>
                        <tr>
                            <td><?= htmlspecialchars($target->target_ayat) ?></td>
                            <td><?= Carbon::parse($target->deadline)->format('d M Y') ?></td>
                            <td><?= $target->deadline < Carbon::now() ? '<span class="badge bg-danger rounded-pill">Lewat</span>' : '<span class="badge bg-success rounded-pill">Aktif</span>' ?></td>
                            <td>
                                <a href="index.php?action=ustadz/target/edit&id=<?= $target->id ?>" class="btn btn-sm btn-outline-maroon rounded-pill">Edit</a>
                                <a href="index.php?action=ustadz/target/destroy&id=<?= $target->id ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>

    <!-- Riwayat Setoran Hafalan -->
    <div class="card-custom p-4 mb-4">
        <h5><i class="bi bi-list-check"></i> Riwayat Setoran Hafalan</h5>
        <?php if ($santri->setoranHafalan->isEmpty()): ?>
            <div class="alert alert-light text-center py-3">Belum ada setoran.</div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light"><tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah</th><th>Nilai</th><th>Catatan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($santri->setoranHafalan as $setoran): ?>
                        <tr>
                            <td><?= Carbon::parse($setoran->tgl_setor)->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($setoran->surat) ?></td>
                            <td><?= $setoran->ayat_mulai ?> - <?= $setoran->ayat_selesai ?></td>
                            <td><?= $setoran->jumlah_ayat ?> ayat</td>
                            <td><span class="badge rounded-pill bg-<?= $setoran->nilai_quality == 'A' ? 'success' : ($setoran->nilai_quality == 'B' ? 'warning' : ($setoran->nilai_quality == 'C' ? 'info' : 'danger')) ?>"><?= $setoran->nilai_quality ?></span></td>
                            <td><?= htmlspecialchars($setoran->catatan) ?></td>
                            <td>
                                <a href="index.php?action=ustadz/setoran/edit_hafalan&id=<?= $setoran->id ?>" class="btn btn-sm btn-outline-maroon rounded-pill">Edit</a>
                                <a href="index.php?action=ustadz/setoran/destroy_hafalan&id=<?= $setoran->id ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
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
                    <thead class="table-light"><tr><th>Tanggal</th><th>Surat</th><th>Ayat</th><th>Jumlah Ulangan</th><th>Aksi</th></tr></thead>
                    <tbody>
                        <?php foreach ($santri->setoranMurajaah as $m): ?>
                        <tr>
                            <td><?= Carbon::parse($m->tgl_murajaah)->format('d/m/Y') ?></td>
                            <td><?= htmlspecialchars($m->surat) ?></td>
                            <td><?= $m->ayat ?></td>
                            <td><?= $m->jumlah_ulangan ?> kali</td>
                            <td>
                                <a href="index.php?action=ustadz/setoran/edit_murajaah&id=<?= $m->id ?>" class="btn btn-sm btn-outline-maroon rounded-pill">Edit</a>
                                <a href="index.php?action=ustadz/setoran/destroy_murajaah&id=<?= $m->id ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')">Hapus</a>
                            </td>
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
        <canvas id="dailyHafalanChart" style="width:100%; height:300px"></canvas>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('dailyHafalanChart'), {
        type: 'line',
        data: {
            labels: <?= $chartLabelsJson ?>,
            datasets: [{ label: 'Jumlah Ayat', data: <?= $chartDataJson ?>, borderColor: '#4A1D2E', backgroundColor: 'rgba(74,29,46,0.05)', borderWidth: 2, fill: true, tension: 0.3, pointBackgroundColor: '#4A1D2E', pointRadius: 3 }]
        },
        options: { responsive: true, maintainAspectRatio: true, plugins: { legend: { position: 'top' } } }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>