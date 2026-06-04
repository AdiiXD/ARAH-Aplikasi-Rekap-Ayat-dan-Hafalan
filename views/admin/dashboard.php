<?php
/** @var int $totalSantri */
/** @var int $totalUstadz */
/** @var int $todaySetoran */
/** @var array $months */
/** @var array $santriData */
/** @var array $ustadzData */
/** @var \Illuminate\Support\Collection $topUstadz */
/** @var int $totalAyatBulanLalu */
/** @var int $totalAyatBulanIni */
/** @var array $dailyLabels */
/** @var array $dailyData */
/** @var \Illuminate\Database\Eloquent\Collection $recentLogs */

$title = "Dashboard Admin";
$activeMenu = "dashboard";
?>
<div class="row">
    <div class="col-md-12">
        <h3>Dashboard Admin</h3>
        <hr>
    </div>
</div>

<!-- Statistik Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card-custom p-3 text-center">
            <i class="bi bi-people fs-1 text-maroon"></i>
            <h2 class="mt-2"><?= $totalSantri ?></h2>
            <span>Total Santri</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom p-3 text-center">
            <i class="bi bi-person-badge fs-1 text-maroon"></i>
            <h2 class="mt-2"><?= $totalUstadz ?></h2>
            <span>Total Ustadz</span>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-custom p-3 text-center">
            <i class="bi bi-journal-bookmark-fill fs-1 text-maroon"></i>
            <h2 class="mt-2"><?= $todaySetoran ?></h2>
            <span>Setoran Hari Ini (Ayat)</span>
        </div>
    </div>
</div>

<!-- Grafik 1 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-custom p-4">
            <h5><i class="bi bi-graph-up"></i> Perkembangan Santri & Ustadz (6 Bulan Terakhir)</h5>
            <canvas id="growthChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Grafik 2 & 3 -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card-custom p-4">
            <h5><i class="bi bi-trophy"></i> Top 5 Ustadz Berdasarkan Setoran Santri</h5>
            <canvas id="topUstadzChart" height="200"></canvas>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4">
            <h5><i class="bi bi-bar-chart-steps"></i> Perbandingan Bulan Ini vs Bulan Lalu</h5>
            <canvas id="comparisonChart" height="200"></canvas>
        </div>
    </div>
</div>

<!-- Grafik 4 -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card-custom p-4">
            <h5><i class="bi bi-graph-up"></i> Tren Setoran Ayat (30 Hari Terakhir)</h5>
            <canvas id="trendChart" height="100"></canvas>
        </div>
    </div>
</div>

<!-- Tombol Backup & Log -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card-custom p-4 text-center">
            <h5><i class="bi bi-database"></i> Backup Database</h5>
            <a href="index.php?action=admin/backup" class="btn btn-maroon mt-2" onclick="return confirm('Yakin melakukan backup?')">Backup Sekarang</a>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-custom p-4 text-center">
            <h5><i class="bi bi-clock-history"></i> Log Aktivitas</h5>
            <a href="index.php?action=admin/logs" class="btn btn-outline-maroon mt-2">Lihat Log</a>
        </div>
    </div>
</div>

<!-- Recent Logs -->
<div class="row">
    <div class="col-md-12">
        <div class="card-custom p-4">
            <h5><i class="bi bi-list-ul"></i> Aktivitas Terbaru</h5>
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr><th>Waktu</th><th>User</th><th>Role</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentLogs as $log): ?>
                        <tr>
                            <td><?= Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') ?></td>
                            <td><?= htmlspecialchars($log->user->name ?? 'Guest') ?></td>
                            <td><?= $log->role ?></td>
                            <td><?= htmlspecialchars($log->action) ?></td>
                            <td><?= htmlspecialchars($log->description) ?></td>
                            <td><?= $log->ip_address ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    new Chart(document.getElementById('growthChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($months) ?>,
            datasets: [
                { label: 'Santri', data: <?= json_encode($santriData) ?>, borderColor: '#4A1D2E', fill: false },
                { label: 'Ustadz', data: <?= json_encode($ustadzData) ?>, borderColor: '#7A3F5A', fill: false }
            ]
        }
    });
    new Chart(document.getElementById('topUstadzChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode($topUstadz->pluck('name')) ?>,
            datasets: [{ label: 'Total Ayat', data: <?= json_encode($topUstadz->pluck('total_ayat')) ?>, backgroundColor: '#4A1D2E' }]
        }
    });
    new Chart(document.getElementById('comparisonChart'), {
        type: 'bar',
        data: {
            labels: ['Bulan Lalu', 'Bulan Ini'],
            datasets: [{ label: 'Total Ayat', data: [<?= $totalAyatBulanLalu ?>, <?= $totalAyatBulanIni ?>], backgroundColor: ['#7A3F5A', '#4A1D2E'] }]
        }
    });
    new Chart(document.getElementById('trendChart'), {
        type: 'line',
        data: {
            labels: <?= json_encode($dailyLabels) ?>,
            datasets: [{ label: 'Jumlah Ayat', data: <?= json_encode($dailyData) ?>, borderColor: '#4A1D2E', fill: false }]
        }
    });
</script>