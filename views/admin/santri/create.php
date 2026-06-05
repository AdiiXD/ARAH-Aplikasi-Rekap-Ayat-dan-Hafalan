<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $ustadzList */
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Kelas[] $kelasList */
$title = "Tambah Santri - ARAH";
$activeMenu = "santri";
ob_start();
?>
<div class="card-custom p-4 mx-auto" style="max-width: 600px;">
    <h3><i class="bi bi-person-plus"></i> Tambah Santri Baru</h3>
    <form method="POST" action="index.php?action=admin/santri/store">
        <div class="mb-3"><label>NIS <span class="text-danger">*</span></label><input type="text" name="nis" class="form-control" placeholder="Contoh: 12345" required></div>
        <div class="mb-3"><label>Nama Lengkap</label><input type="text" name="nama" class="form-control" required></div>
        <div class="mb-3"><label>Nickname (panggilan)</label><input type="text" name="nickname" class="form-control" placeholder="Contoh: Ucup"></div>
        <div class="mb-3"><label>Tanggal Lahir</label><input type="date" name="tanggal_lahir" class="form-control" required></div>
        <div class="mb-3"><label>Tahun Masuk</label><input type="number" name="tahun_masuk" class="form-control" min="2000" max="2030" required></div>
        <div class="mb-3"><label>Ustadz</label><select name="ustadz_id" class="form-select" required><option value="">-- Pilih --</option><?php foreach ($ustadzList as $u): ?><option value="<?= $u->id ?>"><?= htmlspecialchars($u->name) ?> (<?= $u->email ?>)</option><?php endforeach; ?></select></div>
        <div class="mb-3"><label>Kelas</label><select name="kelas_id" class="form-select" required><option value="">-- Pilih --</option><?php foreach ($kelasList as $k): ?><option value="<?= $k->id ?>"><?= htmlspecialchars($k->nama_kelas) ?></option><?php endforeach; ?></select></div>
        <button type="submit" class="btn btn-maroon w-100 rounded-pill">Simpan</button>
        <a href="index.php?action=admin/santri" class="btn btn-secondary w-100 mt-2 rounded-pill">Batal</a>
    </form>
</div>
<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>