<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Notifikasi[] $notifikasi */
$title = "Notifikasi";
$activeMenu = "notifikasi";
ob_start();
?>

<div class="card-custom p-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3><i class="bi bi-bell"></i> Inbox Notifikasi</h3>
        <a href="index.php?action=orangtua/dashboard" class="btn btn-secondary btn-sm">Kembali</a>
    </div>
    <?php if ($notifikasi->isEmpty()): ?>
        <div class="alert alert-light">Tidak ada notifikasi.</div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifikasi as $notif): ?>
            <div class="list-group-item <?= $notif->is_read ? '' : 'list-group-item-warning' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1"><?= htmlspecialchars($notif->pesan) ?></p>
                        <small class="text-muted"><?= Carbon\Carbon::parse($notif->created_at)->diffForHumans() ?></small>
                    </div>
                    <?php if (!$notif->is_read): ?>
                    <a href="index.php?action=orangtua/notifikasi/markAsRead&id=<?= $notif->id ?>" class="btn btn-sm btn-outline-success">Tandai Dibaca</a>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php
$content = ob_get_clean();
include __DIR__ . '/../../layouts/main.php';
?>