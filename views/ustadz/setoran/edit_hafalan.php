<?php
/** @var \App\Models\SetoranHafalan $setoran */
use Carbon\Carbon;
$title = "Edit Setoran Hafalan";
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3>Edit Setoran Hafalan</h3>
    <form method="POST" action="index.php?action=ustadz/setoran/update_hafalan&id=<?= $setoran->id ?>">
        <div class="mb-3"><label>Surat</label><input type="text" name="surat" class="form-control" value="<?= htmlspecialchars($setoran->surat) ?>" required></div>
        <div class="row">
            <div class="col-md-6"><label>Ayat Mulai</label><input type="number" name="ayat_mulai" class="form-control" value="<?= $setoran->ayat_mulai ?>" required></div>
            <div class="col-md-6"><label>Ayat Selesai</label><input type="number" name="ayat_selesai" class="form-control" value="<?= $setoran->ayat_selesai ?>" required></div>
        </div>
        <div class="mb-3"><label>Jumlah Ayat</label><input type="number" name="jumlah_ayat" class="form-control" value="<?= $setoran->jumlah_ayat ?>" required></div>
        <div class="mb-3"><label>Nilai</label>
            <select name="nilai_quality" class="form-select">
                <option value="A" <?= $setoran->nilai_quality == 'A' ? 'selected' : '' ?>>A</option>
                <option value="B" <?= $setoran->nilai_quality == 'B' ? 'selected' : '' ?>>B</option>
                <option value="C" <?= $setoran->nilai_quality == 'C' ? 'selected' : '' ?>>C</option>
                <option value="D" <?= $setoran->nilai_quality == 'D' ? 'selected' : '' ?>>D</option>
            </select>
        </div>
        <div class="mb-3"><label>Catatan</label><textarea name="catatan" class="form-control"><?= htmlspecialchars($setoran->catatan) ?></textarea></div>
        <div class="mb-3"><label>Tanggal Setor</label><input type="date" name="tgl_setor" class="form-control" value="<?= $setoran->tgl_setor ?>"></div>
        <button type="submit" class="btn btn-maroon w-100">Update</button>
        <a href="index.php?action=ustadz/santri/show&id=<?= $setoran->santri_id ?>" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>