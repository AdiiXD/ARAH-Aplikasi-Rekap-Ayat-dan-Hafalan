<?php
/** @var \App\Models\TargetHafalan $target */
$title = "Edit Target";
$activeMenu = "santri";
ob_start();
?>

<div class="card-custom p-4 mx-auto" style="max-width: 500px;">
    <h3>Edit Target Hafalan</h3>
    <form method="POST" action="index.php?action=ustadz/target/update&id=<?= $target->id ?>">
        <div class="mb-3"><label>Target</label><input type="text" name="target_ayat" class="form-control" value="<?= htmlspecialchars($target->target_ayat) ?>" required></div>
        <div class="mb-3"><label>Deadline</label><input type="date" name="deadline" class="form-control" value="<?= $target->deadline ?>" required></div>
        <button type="submit" class="btn btn-maroon w-100">Update</button>
        <a href="index.php?action=ustadz/target/index&id=<?= $target->santri_id ?>" class="btn btn-secondary w-100 mt-2">Batal</a>
    </form>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>