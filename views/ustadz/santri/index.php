<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santriList */
$santriList = $santriList ?? collect();
$title = "Santri Binaan - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-people"></i> Santri Binaan</h3>
        <span class="badge bg-maroon rounded-pill"><?= $santriList->count() ?> Santri</span>
    </div>
    <?php if ($santriList->isEmpty()): ?>
        <div class="text-center py-5">
            <i class="bi bi-person-x fs-1 text-muted"></i>
            <p class="mt-2">Belum ada santri yang dibimbing.</p>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr><th>NIS</th><th>Nama</th><th>Nickname</th><th>Kelas</th><th>Aksi</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($santriList as $s): ?>
                    <tr>
                        <td><?= htmlspecialchars($s->nis ?? '-') ?></td>
                        <td><?= htmlspecialchars($s->nama) ?></td>
                        <td><?= htmlspecialchars($s->nickname ?? '-') ?></td>
                        <td><?= htmlspecialchars($s->kelas->nama_kelas ?? '-') ?></td>
                        <td><a href="index.php?action=ustadz/santri/show&id=<?= $s->id ?>" class="btn btn-sm btn-maroon rounded-pill">Progress</a></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>