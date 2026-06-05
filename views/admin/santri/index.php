<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Santri[] $santri */
$title = "Kelola Santri - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-people"></i> Daftar Santri</h3>
        <a href="index.php?action=admin/santri/create" class="btn btn-maroon rounded-pill"><i class="bi bi-plus-lg"></i> Tambah Santri</a>
    </div>
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
    <?php endif; ?>
    <div class="table-responsive">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr><th>ID</th><th>NIS</th><th>Nama</th><th>Nickname</th><th>Ustadz</th><th>Kelas</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($santri as $s): ?>
                <tr>
                    <td><?= $s->id ?></td>
                    <td><?= htmlspecialchars($s->nis ?? '-') ?></td>
                    <td><?= htmlspecialchars($s->nama) ?></td>
                    <td><?= htmlspecialchars($s->nickname ?? '-') ?></td>
                    <td><?= htmlspecialchars($s->ustadz->name ?? '-') ?></td>
                    <td><?= htmlspecialchars($s->kelas->nama_kelas ?? '-') ?></td>
                    <td>
                        <a href="index.php?action=admin/santri/show&id=<?= $s->id ?>" class="btn btn-sm btn-outline-info rounded-pill"><i class="bi bi-eye"></i></a>
                        <a href="index.php?action=admin/santri/edit&id=<?= $s->id ?>" class="btn btn-sm btn-outline-maroon rounded-pill"><i class="bi bi-pencil"></i></a>
                        <a href="index.php?action=admin/santri/delete&id=<?= $s->id ?>" class="btn btn-sm btn-outline-danger rounded-pill" onclick="return confirm('Yakin hapus?')"><i class="bi bi-trash"></i></a>
                    </td>
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