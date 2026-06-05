<?php
/** @var \App\Models\Santri $santri */
use Carbon\Carbon;
$title = "Tambah Setoran Hafalan - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3 class="mb-4"><i class="bi bi-plus-circle"></i> Setoran Hafalan Baru</h3>
    <p><strong>Santri:</strong> <?= htmlspecialchars($santri->nama) ?></p>
    <form method="POST" action="index.php?action=ustadz/setoran/store_hafalan&id=<?= $santri->id ?>">
        <div class="mb-3"><label>Surat</label><input type="text" name="surat" class="form-control" required></div>
        <div class="row">
            <div class="col-md-6"><label>Ayat Mulai</label><input type="number" name="ayat_mulai" class="form-control" required></div>
            <div class="col-md-6"><label>Ayat Selesai</label><input type="number" name="ayat_selesai" class="form-control" required></div>
        </div>
        <div class="mb-3"><label>Jumlah Ayat</label><input type="number" name="jumlah_ayat" class="form-control" required></div>
        <div class="mb-3"><label>Nilai</label>
            <select name="nilai_quality" class="form-select">
                <option value="A">A (Sangat Baik)</option><option value="B">B (Baik)</option><option value="C">C (Cukup)</option><option value="D">D (Perlu Bimbingan)</option>
            </select>
        </div>
        <div class="mb-3"><label>Catatan</label><textarea name="catatan" class="form-control" rows="2"></textarea></div>
        <div class="mb-3"><label>Tanggal Setor</label><input type="date" name="tgl_setor" class="form-control" value="<?= Carbon::today()->toDateString() ?>"></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Simpan</button>
        <a href="index.php?action=ustadz/santri/show&id=<?= $santri->id ?>" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../../layouts/main.php';
?>