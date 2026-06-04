<?php

/** @var \App\Models\Santri $santri */

use Carbon\Carbon;

$title = "Progress Hafalan: " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4 mb-4">
    <div class="d-flex justify-content-between">
        <h3><?= htmlspecialchars($santri->nama) ?></h3>
        <a href="index.php?action=orangtua/dashboard" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>
    <hr>
    <div class="row">
        <div class="col-md-6"><strong>NIS:</strong> <?= htmlspecialchars($santri->nis) ?></div>
        <div class="col-md-6"><strong>Nickname:</strong> <?= htmlspecialchars($santri->nickname) ?></div>
        <div class="col-md-6"><strong>Kelas:</strong> <?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></div>
        <div class="col-md-6"><strong>Ustadz:</strong> <?= htmlspecialchars($santri->ustadz->name ?? '-') ?></div>
        <div class="col-md-6"><strong>Tahun Masuk:</strong> <?= $santri->tahun_masuk ?></div>
        <div class="col-md-6"><strong>Tanggal Lahir:</strong> <?= Carbon::parse($santri->tanggal_lahir)->format('d M Y') ?></div>
    </div>
</div>

<!-- Target Hafalan -->
<div class="card-custom p-4 mb-4">
    <h5><i class="bi bi-bullseye"></i> Target Hafalan</h5>
    <?php if ($santri->targetHafalan->isEmpty()): ?>
        <p class="text-muted">Belum ada target yang ditetapkan.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead class="table-light">
                    <tr>
                        <th>Target</th>
                        <th>Deadline</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($santri->targetHafalan as $target): ?>
                        <tr>
                            <td><?= htmlspecialchars($target->target_ayat) ?></td>
                            <td><?= Carbon::parse($target->deadline)->format('d M Y') ?> <?= $target->deadline < Carbon::now() ? '<span class="badge bg-danger">Lewat</span>' : '' ?></td>
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
        <p class="text-muted">Belum ada setoran hafalan.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Surat</th>
                        <th>Ayat</th>
                        <th>Jumlah</th>
                        <th>Nilai</th>
                        <th>Catatan</th>
                    </tr>
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

<!-- Riwayat Murajaah -->
<div class="card-custom p-4 mb-4">
    <h5><i class="bi bi-arrow-repeat"></i> Riwayat Murajaah</h5>
    <?php if ($santri->setoranMurajaah->isEmpty()): ?>
        <p class="text-muted">Belum ada setoran murajaah.</p>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Tanggal</th>
                        <th>Surat</th>
                        <th>Ayat</th>
                        <th>Jumlah Ulangan</th>
                    </tr>
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

<!-- Grafik -->
<div class="card-custom p-4">
    <h5><i class="bi bi-graph-up"></i> Grafik Jumlah Ayat per Bulan</h5>
    <canvas id="hafalanChart" width="400" height="200"></canvas>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const labels = <?php
                    $bulanData = [];
                    $jumlahData = [];
                    foreach (
                        $santri->setoranHafalan->groupBy(function ($item) {
                            return Carbon::parse($item->tgl_setor)->format('Y-m');
                        }) as $bulan => $setorans
                    ) {
                        $bulanData[] = $bulan;
                        $jumlahData[] = $setorans->sum('jumlah_ayat');
                    }
                    echo json_encode($bulanData);
                    ?>;
    const dataJumlah = <?= json_encode($jumlahData) ?>;
    new Chart(document.getElementById('hafalanChart'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Jumlah Ayat',
                data: dataJumlah,
                backgroundColor: '#4A1D2E'
            }]
        }
    });
</script>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>