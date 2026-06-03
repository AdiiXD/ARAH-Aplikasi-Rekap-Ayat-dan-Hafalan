<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santriList */
$title = "Santri Binaan";
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4">
    <h3>Daftar Santri Binaan</h3>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead><tr><th>Nama</th><th>Kelas</th><th>Aksi</th></tr></thead>
            <tbody>
            <?php foreach ($santriList as $santri): ?>
            <tr>
                <td><?= htmlspecialchars($santri->nama) ?></td>
                <td><?= htmlspecialchars($santri->kelas->nama_kelas ?? '-') ?></td>
                <td><a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-sm btn-maroon">Progress</a></td>
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