<?php
$title = "Dashboard Admin";
$activeMenu = "dashboard";
ob_start();
?>



<!-- Rest of dashboard content (statistik, dll) -->
<div class="card-custom p-4">
    <h3>Selamat datang, <?= htmlspecialchars($_SESSION['name'] ?? 'Admin') ?></h3>
    <p class="text-muted">Dashboard administrator Hafalan Tracker</p>
    <hr>
    <div class="row">
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-people fs-1 text-maroon"></i>
                <h4 id="totalSantri">0</h4>
                <span>Total Santri</span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-person-badge fs-1 text-maroon"></i>
                <h4 id="totalUstadz">0</h4>
                <span>Total Ustadz</span>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card text-center p-3 border-0 shadow-sm">
                <i class="bi bi-journal-bookmark-fill fs-1 text-maroon"></i>
                <h4 id="totalSetoranHariIni">0</h4>
                <span>Setoran Hari Ini</span>
            </div>
        </div>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../layouts/main.php';
?>