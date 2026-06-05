<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
$title = "Tambah Murajaah - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3><i class="bi bi-arrow-repeat"></i> Setoran Murajaah</h3>
    <p><strong>Santri:</strong> <?= htmlspecialchars($santri->nama) ?></p>
    <form method="POST" action="index.php?action=ustadz/setoran/store_murajaah&id=<?= $santri->id ?>">
        <div class="mb-3"><label>Surat</label><input type="text" name="surat" class="form-control" required></div>
        <div class="mb-3"><label>Ayat</label><input type="number" name="ayat" class="form-control" required></div>
        <div class="mb-3"><label>Jumlah Ulangan</label><input type="number" name="jumlah_ulangan" class="form-control" required></div>
        <div class="mb-3"><label>Tanggal Murajaah</label><input type="date" name="tgl_murajaah" class="form-control" value="<?= Carbon::today()->toDateString() ?>"></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Simpan</button>
        <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../layouts/main.php';
?>