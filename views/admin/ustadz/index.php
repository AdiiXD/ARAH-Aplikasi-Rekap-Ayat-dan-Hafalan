<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $ustadz */
$title = "Kelola Ustadz";
$activeMenu = "ustadz";
ob_start();
?>

<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Ustadz</h3>
        <a href="index.php?action=admin/ustadz/create" class="btn btn-maroon"><i class="bi bi-plus-lg"></i> Tambah Ustadz</a>
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
                <tr><th>#</th><th>Nama Ustadz</th><th>Email</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($ustadz as $u): ?>
                <tr>
                    <td><?= $u->id ?></td>
                    <td><?= htmlspecialchars($u->name) ?></td>
                    <td><?= htmlspecialchars($u->email) ?></td>
                    <td>
                        <a href="index.php?action=admin/ustadz/edit&id=<?= $u->id ?>" class="btn btn-sm btn-outline-maroon"><i class="bi bi-pencil"></i></a>
                        <a href="index.php?action=admin/ustadz/delete&id=<?= $u->id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus ustadz ini?')"><i class="bi bi-trash"></i></a>
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