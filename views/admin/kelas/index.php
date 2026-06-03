<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Kelas[] $kelas */
$title = "Kelola Kelas";
$activeMenu = "kelas";
ob_start();
?>

<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3>Daftar Kelas</h3>
        <a href="index.php?action=admin/kelas/create" class="btn btn-maroon"><i class="bi bi-plus-lg"></i> Tambah Kelas</a>
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
                <tr><th>#</th><th>Nama Kelas</th><th>Deskripsi</th><th>Aksi</th></tr>
            </thead>
            <tbody>
                <?php foreach ($kelas as $k): ?>
                <tr>
                    <td><?= $k->id ?></td>
                    <td><?= htmlspecialchars($k->nama_kelas) ?></td>
                    <td><?= htmlspecialchars($k->deskripsi) ?></td>
                    <td>
                        <a href="index.php?action=admin/kelas/edit&id=<?= $k->id ?>" class="btn btn-sm btn-outline-maroon"><i class="bi bi-pencil"></i></a>
                        <a href="index.php?action=admin/kelas/delete&id=<?= $k->id ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin hapus kelas ini?')"><i class="bi bi-trash"></i></a>
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