<?php
$role = $_SESSION['role'] ?? 'guest';
$name = $_SESSION['name'] ?? 'Pengguna';
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $title ?? 'Hafalan Tracker' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon-dark: #4A1D2E;
            --maroon-light: #7A3F5A;
            --cream-bg: #FDF8F0;
            --cream-card: #FFF9EF;
            --text-dark: #2C2C2C;
            --text-muted: #6B6B6B;
            --border-light: #E6DDD0;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--cream-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }
        /* Navbar sticky */
        .navbar-maroon {
            background: var(--maroon-dark);
            box-shadow: 0 2px 8px rgba(0,0,0,0.05);
            position: sticky;
            top: 0;
            z-index: 1030;
        }
        .navbar-maroon .navbar-brand {
            font-weight: 600;
            letter-spacing: -0.3px;
        }
        /* Offcanvas tanpa animasi */
        .offcanvas {
            background: var(--cream-card);
            border-right: 1px solid var(--border-light);
            box-shadow: 4px 0 12px rgba(0,0,0,0.05);
            transition: none; /* Hapus animasi */
        }
        .offcanvas-header {
            border-bottom: 1px solid var(--border-light);
            padding: 1.2rem;
        }
        .offcanvas-body {
            padding: 1rem;
        }
        .offcanvas .nav-link {
            color: var(--text-dark);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            margin-bottom: 0.3rem;
            transition: background 0.2s, color 0.2s;
            font-weight: 500;
        }
        .offcanvas .nav-link:hover,
        .offcanvas .nav-link.active {
            background: var(--maroon-dark);
            color: white;
        }
        .offcanvas .nav-link i {
            margin-right: 8px;
            width: 24px;
            text-align: center;
        }
        /* Tombol hamburger tanpa animasi */
        .hamburger-animate {
            transition: none;
        }
        .card-custom {
            background: var(--cream-card);
            border-radius: 24px;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            padding: 1.5rem;
        }
        .btn-maroon {
            background: var(--maroon-dark);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            transition: background 0.2s;
            border: none;
        }
        .btn-maroon:hover {
            background: var(--maroon-light);
        }
        .btn-outline-maroon {
            border: 1px solid var(--maroon-dark);
            color: var(--maroon-dark);
            border-radius: 50px;
            background: transparent;
        }
        .btn-outline-maroon:hover {
            background: var(--maroon-dark);
            color: white;
        }
        @media (max-width: 768px) {
            .card-custom {
                padding: 1rem;
            }
            h3 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-maroon navbar-dark">
    <div class="container-fluid">
        <?php if (in_array($role, ['admin', 'ustadz', 'orangtua'])): ?>
        <button class="btn btn-outline-light btn-sm me-2 hamburger-animate" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            <i class="bi bi-list"></i>
        </button>
        <?php endif; ?>
        <span class="navbar-brand"><i class="bi bi-book-half"></i> Hafalan Tracker</span>
        <div class="d-flex gap-2">
            <?php if ($role === 'orangtua'): ?>
                <a href="index.php?action=orangtua/notifikasi" class="btn btn-outline-light btn-sm">
                    <i class="bi bi-bell"></i> Notif
                </a>
            <?php endif; ?>
            <a href="index.php?action=reports" class="btn btn-outline-light btn-sm">
                <i class="bi bi-file-earmark-text"></i> Laporan
            </a>
            <a href="index.php?action=logout" class="btn btn-outline-light btn-sm">
                <i class="bi bi-box-arrow-right"></i> Logout
            </a>
        </div>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel"><i class="bi bi-grid"></i> Menu</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="nav flex-column">
            <?php if ($role === 'admin'): ?>
                <li class="nav-item"><a href="index.php?action=admin/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="index.php?action=admin/ustadz" class="nav-link <?= ($activeMenu ?? '') == 'ustadz' ? 'active' : '' ?>"><i class="bi bi-person-badge"></i> Kelola Ustadz</a></li>
                <li class="nav-item"><a href="index.php?action=admin/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>"><i class="bi bi-people"></i> Kelola Santri</a></li>
                <li class="nav-item"><a href="index.php?action=admin/kelas" class="nav-link <?= ($activeMenu ?? '') == 'kelas' ? 'active' : '' ?>"><i class="bi bi-building"></i> Kelola Kelas</a></li>
                <li class="nav-item"><a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <?php elseif ($role === 'ustadz'): ?>
                <li class="nav-item"><a href="index.php?action=ustadz/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="index.php?action=ustadz/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>"><i class="bi bi-people"></i> Santri Binaan</a></li>
                <li class="nav-item"><a href="index.php?action=quran" class="nav-link <?= ($activeMenu ?? '') == 'quran' ? 'active' : '' ?>"><i class="bi bi-book"></i> Baca Quran</a></li>
                <li class="nav-item"><a href="index.php?action=tajweed-guide" class="nav-link <?= ($activeMenu ?? '') == 'tajweed_guide' ? 'active' : '' ?>"><i class="bi bi-palette"></i> Panduan Tajwid</a></li>
                <li class="nav-item"><a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <?php elseif ($role === 'orangtua'): ?>
                <li class="nav-item"><a href="index.php?action=orangtua/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>"><i class="bi bi-speedometer2"></i> Dashboard</a></li>
                <li class="nav-item"><a href="index.php?action=orangtua/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>"><i class="bi bi-people"></i> Anak Saya</a></li>
                <li class="nav-item"><a href="index.php?action=quran" class="nav-link <?= ($activeMenu ?? '') == 'quran' ? 'active' : '' ?>"><i class="bi bi-book"></i> Baca Quran</a></li>
                <li class="nav-item"><a href="index.php?action=tajweed-guide" class="nav-link <?= ($activeMenu ?? '') == 'tajweed_guide' ? 'active' : '' ?>"><i class="bi bi-palette"></i> Panduan Tajwid</a></li>
                <li class="nav-item"><a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>"><i class="bi bi-gear"></i> Pengaturan</a></li>
            <?php endif; ?>
        </ul>
    </div>
</div>

<div class="container mt-4">
    <?= $content ?? '<div class="alert alert-warning">Konten tidak tersedia.</div>' ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Tutup offcanvas otomatis di mobile
    document.querySelectorAll('.offcanvas .nav-link').forEach(link => {
        link.addEventListener('click', () => {
            const offcanvas = bootstrap.Offcanvas.getInstance(document.getElementById('sidebarOffcanvas'));
            if (offcanvas && window.innerWidth < 992) {
                offcanvas.hide();
            }
        });
    });
</script>
</body>
</html>