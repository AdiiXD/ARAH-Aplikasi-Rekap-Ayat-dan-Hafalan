<?php
/** @var \Illuminate\Database\Eloquent\Collection|\App\Models\Notifikasi[] $notifikasi */
$title = "Notifikasi ARAH";
$activeMenu = "notifikasi";
ob_start();
?>
<div class="card-custom p-4">
    <h3 class="mb-3"><i class="bi bi-bell"></i> Inbox Notifikasi</h3>
    <?php if ($notifikasi->isEmpty()): ?>
        <div class="alert alert-light text-center p-5">
            <i class="bi bi-envelope-open fs-1 text-muted"></i>
            <p class="mt-2">Tidak ada notifikasi baru.</p>
        </div>
    <?php else: ?>
        <div class="list-group">
            <?php foreach ($notifikasi as $notif): ?>
            <div class="list-group-item <?= $notif->is_read ? 'bg-light' : 'border-start border-4 border-maroon' ?>">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="mb-1"><?= htmlspecialchars($notif->pesan) ?></p>
                        <small class="text-muted"><?= Carbon\Carbon::parse($notif->created_at)->diffForHumans() ?></small>
                    </div>
                    <?php if (!$notif->is_read): ?>
                    <a href="index.php?action=orangtua/notifikasi/markAsRead&id=<?= $notif->id ?>" class="btn btn-sm btn-outline-success rounded-pill">Tandai Dibaca</a>
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