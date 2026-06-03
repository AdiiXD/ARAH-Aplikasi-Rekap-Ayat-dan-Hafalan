<?php
/** @var \App\Models\User $ustadz */
$title = "Edit Ustadz";
$activeMenu = "ustadz";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Edit Ustadz</h3>
    <form method="POST" action="index.php?action=admin/ustadz/update&id=<?= $ustadz->id ?>">
        <div class="mb-3">
            <label class="form-label">Nama Lengkap</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($ustadz->name) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($ustadz->email) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Password (Opsional)</label>
            <input type="password" name="password" class="form-control" placeholder="Kosongkan jika tidak diubah">
        </div>
        <button type="submit" class="btn btn-maroon w-100">Update</button>
        <a href="index.php?action=admin/ustadz" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>