<?php
/** @var \App\Models\Santri $santri */
/** @var \Illuminate\Database\Eloquent\Collection $targets */
$title = "Target Hafalan - " . htmlspecialchars($santri->nama);
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3>Target Hafalan: <?= htmlspecialchars($santri->nama) ?></h3>
        <a href="index.php?action=ustadz/target/create&id=<?= $santri->id ?>" class="btn btn-maroon rounded-pill"><i class="bi bi-plus-lg"></i> Tambah Target</a>
    </div>
    <?php if ($targets->isEmpty()): ?>
        <div class="alert alert-light text-center py-3">Belum ada target.</div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light"><tr><th>Target</th><th>Deadline</th><th>Aksi</th></tr></thead>
                <tbody>
                    <?php foreach ($targets as $target): ?>
                    <tr>
                        <td><?= htmlspecialchars($target->target_ayat) ?></td>
                        <td><?= Carbon\Carbon::parse($target->deadline)->format('d M Y') ?></td>
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
    <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-secondary rounded-pill mt-3">Kembali</a>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>