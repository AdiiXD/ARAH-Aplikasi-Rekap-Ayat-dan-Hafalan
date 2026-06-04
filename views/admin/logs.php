<?php
/** @var \Illuminate\Database\Eloquent\Collection $logs */
$title = "Log Aktivitas";
$activeMenu = "logs";
?>
<div class="card-custom p-4">
    <div class="d-flex justify-content-between mb-3">
        <h3><i class="bi bi-clock-history"></i> Log Aktivitas</h3>
        <a href="index.php?action=admin/clear-logs" class="btn btn-danger btn-sm" onclick="return confirm('Hapus semua log?')">Hapus Semua Log</a>
    </div>
    <div class="table-responsive">
        <table class="table table-hover">
            <thead>
                <tr><th>ID</th><th>Waktu</th><th>User</th><th>Role</th><th>Aksi</th><th>Deskripsi</th><th>IP</th></tr>
            </thead>
            <tbody>
                <?php if ($logs->count() > 0): ?>
                    <?php foreach ($logs as $log): ?>
                    <tr>
                        <td><?= $log->id ?></td>
                        <td><?= Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i:s') ?></td>
                        <td><?= htmlspecialchars($log->user->name ?? 'Guest') ?></td>
                        <td><?= $log->role ?></td>
                        <td><?= htmlspecialchars($log->action) ?></td>
                        <td><?= htmlspecialchars($log->description) ?></td>
                        <td><?= $log->ip_address ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" class="text-center">Belum ada log aktivitas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <div class="text-muted mt-2">Menampilkan 100 log terbaru.</div>
</div>