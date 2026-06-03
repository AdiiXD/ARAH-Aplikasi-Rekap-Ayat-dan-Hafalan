<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $anakList */
$title = "Daftar Anak";
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4">
    <h3>Daftar Anak</h3>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
                <tr><th>Nama</th><th>Kelas</th><th>Ustadz</th><th>Aksi</th></tr>
            </thead>
            <tbody>
            <?php foreach ($anakList as $anak): ?>
            <tr>
                <td><?= htmlspecialchars($anak->nama) ?></td>
                <td><?= htmlspecialchars($anak->kelas->nama_kelas ?? '-') ?></td>
                <td><?= htmlspecialchars($anak->ustadz->name ?? '-') ?></td>
                <td><a href="index.php?action=orangtua/santri/show&id=<?= $anak->id ?>" class="btn btn-sm btn-maroon">Progress</a></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>