<?php
/** @var \App\Models\SetoranMurajaah $murajaah */
use Carbon\Carbon;
$title = "Edit Murajaah - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3><i class="bi bi-pencil-square"></i> Edit Murajaah</h3>
    <form method="POST" action="index.php?action=ustadz/setoran/update_murajaah&id=<?= $murajaah->id ?>">
        <div class="mb-3"><label>Surat</label><input type="text" name="surat" class="form-control" value="<?= htmlspecialchars($murajaah->surat) ?>" required></div>
        <div class="mb-3"><label>Ayat</label><input type="number" name="ayat" class="form-control" value="<?= $murajaah->ayat ?>" required></div>
        <div class="mb-3"><label>Jumlah Ulangan</label><input type="number" name="jumlah_ulangan" class="form-control" value="<?= $murajaah->jumlah_ulangan ?>" required></div>
        <div class="mb-3"><label>Tanggal Murajaah</label><input type="date" name="tgl_murajaah" class="form-control" value="<?= $murajaah->tgl_murajaah ?>"></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Update</button>
        <a href="index.php?action=ustadz/santri/show&id=<?= $murajaah->santri_id ?>" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../layouts/main.php';
?>