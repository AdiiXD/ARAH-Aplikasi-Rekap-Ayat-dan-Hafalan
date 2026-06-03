<?php
/** @var \App\Models\Santri $santri */
/** @var \Illuminate\Database\Eloquent\Collection $targets */
$title = "Target Hafalan - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4">
    <div class="d-flex justify-content-between">
        <h3>Target Hafalan: <?= htmlspecialchars($santri->nama) ?></h3>
        <a href="index.php?action=ustadz/target/create&id=<?= $santri->id ?>" class="btn btn-maroon btn-sm">Tambah Target</a>
    </div>
    <table class="table">
        <thead><tr><th>Target</th><th>Deadline</th><th>Aksi</th></tr></thead>
        <tbody>
        <?php foreach ($targets as $target): ?>
        <tr>
            <td><?= htmlspecialchars($target->target_ayat) ?></td>
            <td><?= Carbon\Carbon::parse($target->deadline)->format('d M Y') ?></td>
            <td>
                <a href="index.php?action=ustadz/target/edit&id=<?= $target->id ?>" class="btn btn-sm btn-outline-maroon">Edit</a>
                <a href="index.php?action=ustadz/target/destroy&id=<?= $target->id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus?')">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-secondary">Kembali</a>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>