<?php
// Pastikan variabel ada (fallback jika undefined)
$weeks = $weeks ?? [];
$data = $data ?? [];
?>
<div class="card-custom p-4">
    <h3><i class="bi bi-graph-up"></i> Statistik Hafalan Mingguan</h3>
    <p class="text-muted">Jumlah ayat yang disetorkan (dari santri binaan/anak) per minggu.</p>
    <canvas id="weeklyChart" height="150"></canvas>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('weeklyChart').getContext('2d');
    new Chart(ctx, {
        type: 'bar',
        data: {
            labels: <?= json_encode($weeks) ?>,
            datasets: [{
                label: 'Total Ayat',
                data: <?= json_encode($data) ?>,
                backgroundColor: '#4A1D2E',
                borderRadius: 6
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: { legend: { position: 'top' } }
        }
    });
</script>