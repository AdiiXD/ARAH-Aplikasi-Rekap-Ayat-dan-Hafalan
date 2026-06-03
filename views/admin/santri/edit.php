<?php
/** @var \App\Models\Santri $santri */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $ustadzList */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Kelas[] $kelasList */
$title = "Edit Santri";
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4">Edit Santri</h3>
    <form method="POST" action="index.php?action=admin/santri/update&id=<?= $santri->id ?>">
        <div class="mb-3">
            <label class="form-label">Nama Santri</label>
            <input type="text" name="nama" class="form-control" value="<?= htmlspecialchars($santri->nama) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tanggal Lahir</label>
            <input type="date" name="tanggal_lahir" class="form-control" value="<?= $santri->tanggal_lahir ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Tahun Masuk</label>
            <input type="number" name="tahun_masuk" class="form-control" value="<?= $santri->tahun_masuk ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Ustadz</label>
            <select name="ustadz_id" class="form-select" required>
                <option value="">-- Pilih Ustadz --</option>
                <?php foreach ($ustadzList as $u): ?>
                <option value="<?= $u->id ?>" <?= $u->id == $santri->ustadz_id ? 'selected' : '' ?>><?= htmlspecialchars($u->name) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Kelas</label>
            <select name="kelas_id" class="form-select" required>
                <option value="">-- Pilih Kelas --</option>
                <?php foreach ($kelasList as $k): ?>
                <option value="<?= $k->id ?>" <?= $k->id == $santri->kelas_id ? 'selected' : '' ?>><?= htmlspecialchars($k->nama_kelas) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <button type="submit" class="btn btn-maroon w-100">Update</button>
        <a href="index.php?action=admin/santri" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>