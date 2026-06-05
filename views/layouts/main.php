<?php
$role = $_SESSION['role'] ?? 'guest';
$name = $_SESSION['name'] ?? 'Pengguna';
$isOrangTua = ($role === 'orangtua');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $title ?? 'ARAH - Aplikasi Rekap Ayat dan Hafalan' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --maroon-dark: #4A1D2E;
            --maroon-light: #7A3F5A;
            --cream-bg: #FDF8F0;
            --cream-card: #FFF9EF;
            --text-dark: #1E1E1E;
            --text-muted: #6B6B6B;
            --border-light: #E6DDD0;
            --shadow-sm: 0 4px 12px rgba(0,0,0,0.05);
            --shadow-md: 0 8px 24px rgba(0,0,0,0.08);
            --transition: all 0.2s ease;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: var(--cream-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }
        /* Navbar */
        .navbar-arah {
            background: var(--maroon-dark);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1030;
            padding: 0.6rem 1rem;
        }
        .navbar-arah .navbar-brand {
            font-weight: 700;
            letter-spacing: -0.5px;
            font-size: 1.4rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .navbar-arah .navbar-brand i {
            font-size: 1.6rem;
        }
        /* Offcanvas Sidebar */
        .offcanvas {
            background: var(--cream-card);
            border-right: 1px solid var(--border-light);
            box-shadow: var(--shadow-md);
        }
        .offcanvas-header {
            border-bottom: 1px solid var(--border-light);
            padding: 1.2rem;
        }
        .offcanvas-header .offcanvas-title {
            font-weight: 600;
            color: var(--maroon-dark);
        }
        .offcanvas-body {
            padding: 1rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .offcanvas .nav-link {
            color: var(--text-dark);
            border-radius: 16px;
            padding: 0.7rem 1rem;
            margin-bottom: 0.3rem;
            transition: var(--transition);
            font-weight: 500;
        }
        .offcanvas .nav-link:hover,
        .offcanvas .nav-link.active {
            background: var(--maroon-dark);
            color: white;
            transform: translateX(4px);
        }
        .offcanvas .nav-link i {
            margin-right: 12px;
            width: 24px;
            text-align: center;
        }
        .sidebar-bottom {
            border-top: 1px solid var(--border-light);
            margin-top: 1rem;
            padding-top: 0.8rem;
        }
        /* Card Modern */
        .card-custom {
            background: var(--cream-card);
            border-radius: 28px;
            border: none;
            box-shadow: var(--shadow-sm);
            transition: var(--transition);
        }
        .card-custom:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-md);
        }
        /* Tombol */
        .btn-maroon {
            background: var(--maroon-dark);
            color: white;
            border-radius: 40px;
            padding: 10px 24px;
            transition: var(--transition);
            border: none;
            font-weight: 500;
        }
        .btn-maroon:hover {
            background: var(--maroon-light);
            transform: translateY(-2px);
            box-shadow: var(--shadow-sm);
        }
        .btn-outline-maroon {
            border: 1px solid var(--maroon-dark);
            color: var(--maroon-dark);
            border-radius: 40px;
            background: transparent;
            transition: var(--transition);
        }
        .btn-outline-maroon:hover {
            background: var(--maroon-dark);
            color: white;
            transform: translateY(-2px);
        }
        .stat-badge {
            background: rgba(74,29,46,0.08);
            border-radius: 60px;
            padding: 0.3rem 1rem;
            display: inline-flex;
            align-items: center;
            gap: 6px;
        }
        /* Efek Khusus */
        .hover-lift {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .hover-lift:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
        }
        @media (max-width: 768px) {
            .card-custom {
                padding: 1rem;
            }
            h3 {
                font-size: 1.4rem;
            }
            .navbar-arah .navbar-brand {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

<nav class="navbar navbar-arah navbar-dark">
    <div class="container-fluid">
        <?php if (in_array($role, ['admin', 'ustadz', 'orangtua'])): ?>
        <button class="btn btn-outline-light btn-sm me-2" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarOffcanvas" aria-controls="sidebarOffcanvas">
            <i class="bi bi-list"></i>
        </button>
        <?php endif; ?>
        <span class="navbar-brand">
            <i class="bi bi-book-half"></i> ARAH
        </span>
        <?php if ($role === 'orangtua'): ?>
        <div class="d-flex gap-2">
            <a href="index.php?action=orangtua/notifikasi" class="btn btn-outline-light btn-sm rounded-pill">
                <i class="bi bi-bell"></i> Notif
            </a>
        </div>
        <?php endif; ?>
    </div>
</nav>

<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarOffcanvas" aria-labelledby="sidebarOffcanvasLabel">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="sidebarOffcanvasLabel"><i class="bi bi-grid"></i> Menu ARAH</h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Tutup"></button>
    </div>
    <div class="offcanvas-body">
        <div>
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
                    <li class="nav-item"><a href="index.php?action=orangtua/anak/create" class="nav-link <?= ($activeMenu ?? '') == 'tambah_anak' ? 'active' : '' ?>"><i class="bi bi-person-plus"></i> Tambah Anak</a></li>
                    <li class="nav-item"><a href="index.php?action=quran" class="nav-link <?= ($activeMenu ?? '') == 'quran' ? 'active' : '' ?>"><i class="bi bi-book"></i> Baca Quran</a></li>
                    <li class="nav-item"><a href="index.php?action=tajweed-guide" class="nav-link <?= ($activeMenu ?? '') == 'tajweed_guide' ? 'active' : '' ?>"><i class="bi bi-palette"></i> Panduan Tajwid</a></li>
                    <li class="nav-item"><a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>"><i class="bi bi-gear"></i> Pengaturan</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="sidebar-bottom">
            <ul class="nav flex-column">
                <li class="nav-item"><a href="index.php?action=reports" class="nav-link"><i class="bi bi-file-earmark-text"></i> Laporan</a></li>
                <li class="nav-item"><a href="index.php?action=logout" class="nav-link text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</div>

<div class="container mt-4">
    <?= $content ?? '<div class="alert alert-warning">Konten tidak tersedia.</div>' ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script>
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