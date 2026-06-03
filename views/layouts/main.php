<?php
// Ambil role dan nama dari session
$role = $_SESSION['role'] ?? 'guest';
$name = $_SESSION['name'] ?? 'Pengguna';
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
    <title><?= $title ?? 'Hafalan Tracker' ?></title>
    <!-- Bootstrap 5 + Icons + Font Inter -->
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

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--cream-bg);
            font-family: 'Inter', sans-serif;
            color: var(--text-dark);
        }

        /* Navbar maroon */
        .navbar-maroon {
            background: var(--maroon-dark);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        }

        .navbar-maroon .navbar-brand {
            font-weight: 600;
            letter-spacing: -0.3px;
        }

        /* Sidebar */
        .sidebar {
            background: var(--cream-card);
            border-radius: 24px;
            padding: 1.2rem;
            height: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            border: 1px solid var(--border-light);
        }

        .sidebar .nav-link {
            color: var(--text-dark);
            border-radius: 12px;
            padding: 0.6rem 1rem;
            margin-bottom: 0.3rem;
            transition: all 0.2s;
            font-weight: 500;
        }

        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            background: var(--maroon-dark);
            color: white;
        }

        .sidebar .nav-link i {
            margin-right: 8px;
            width: 24px;
            text-align: center;
        }

        .sidebar h5 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--maroon-dark);
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid var(--border-light);
        }

        /* Card custom */
        .card-custom {
            background: var(--cream-card);
            border-radius: 24px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            padding: 1.5rem;
        }

        /* Tombol maroon */
        .btn-maroon {
            background: var(--maroon-dark);
            color: white;
            border-radius: 50px;
            padding: 8px 20px;
            transition: all 0.2s;
            border: none;
        }

        .btn-maroon:hover {
            background: var(--maroon-light);
            color: white;
            transform: translateY(-1px);
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

        /* Tabel responsif */
        .table-responsive-custom {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table th {
            font-weight: 600;
            color: var(--maroon-dark);
            border-bottom-width: 1px;
        }

        /* Form */
        .form-control,
        .form-select {
            border-radius: 16px;
            border: 1px solid var(--border-light);
            padding: 0.65rem 1rem;
            background: white;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: var(--maroon-dark);
            box-shadow: 0 0 0 3px rgba(74, 29, 46, 0.1);
        }

        label {
            font-weight: 600;
            color: var(--maroon-dark);
            margin-bottom: 0.4rem;
            font-size: 0.85rem;
        }

        /* Alert */
        .alert-custom {
            border-radius: 16px;
            border: none;
        }

        /* Utility */
        .text-maroon {
            color: var(--maroon-dark);
        }

        .bg-maroon {
            background: var(--maroon-dark);
        }

        /* Mobile first adjustments */
        @media (max-width: 768px) {
            .sidebar {
                margin-bottom: 1.5rem;
                padding: 1rem;
            }

            .card-custom {
                padding: 1rem;
            }

            .btn-sm-mobile {
                font-size: 0.75rem;
                padding: 0.3rem 0.6rem;
            }

            h3 {
                font-size: 1.4rem;
            }
        }
    </style>
</head>

<body>

    <!-- Navbar -->
    <nav class="navbar navbar-maroon navbar-dark">
        <div class="container-fluid">
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

    <div class="container mt-4">
        <div class="row">
            <!-- Sidebar untuk role yang membutuhkan menu -->
            <?php if (in_array($role, ['admin', 'ustadz', 'orangtua'])): ?>
                <div class="col-md-3 mb-4">
                    <div class="sidebar">
                        <h5><i class="bi bi-grid"></i> Menu</h5>
                        <ul class="nav flex-column">
                            <?php if ($role === 'admin'): ?>
                                <li class="nav-item">
                                    <a href="index.php?action=admin/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=admin/ustadz" class="nav-link <?= ($activeMenu ?? '') == 'ustadz' ? 'active' : '' ?>">
                                        <i class="bi bi-person-badge"></i> Kelola Ustadz
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=admin/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>">
                                        <i class="bi bi-people"></i> Kelola Santri
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=admin/kelas" class="nav-link <?= ($activeMenu ?? '') == 'kelas' ? 'active' : '' ?>">
                                        <i class="bi bi-building"></i> Kelola Kelas
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>">
                                        <i class="bi bi-gear"></i> Pengaturan
                                    </a>
                                </li>
                            <?php elseif ($role === 'ustadz'): ?>
                                <li class="nav-item">
                                    <a href="index.php?action=ustadz/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>">
                                        <i class="bi bi-speedometer2"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=ustadz/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>">
                                        <i class="bi bi-people"></i> Santri Binaan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=quran" class="nav-link <?= ($activeMenu ?? '') == 'quran' ? 'active' : '' ?>">
                                        <i class="bi bi-book"></i> Baca Quran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=tajweed-guide" class="nav-link <?= ($activeMenu ?? '') == 'tajweed_guide' ? 'active' : '' ?>">
                                        <i class="bi bi-palette"></i> Panduan Tajwid
                                    </a>
                                </li>
                                <li class="nav-item"><a href="index.php?action=bookmark" class="nav-link"><i class="bi bi-bookmark"></i> Bookmark</a></li>
                                <li class="nav-item"><a href="index.php?action=statistics/weekly" class="nav-link"><i class="bi bi-graph-up"></i> Statistik Hafalan</a></li>
                                <li class="nav-item">
                                    <a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>">
                                        <i class="bi bi-gear"></i> Pengaturan
                                    </a>
                                </li>
                            <?php elseif ($role === 'orangtua'): ?>
                                <li class="nav-item">
                                    <a href="index.php?action=orangtua/dashboard" class="nav-link <?= ($activeMenu ?? '') == 'dashboard' ? 'active' : '' ?>">
                                        <i class="bi bi-house"></i> Dashboard
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=orangtua/santri" class="nav-link <?= ($activeMenu ?? '') == 'santri' ? 'active' : '' ?>">
                                        <i class="bi bi-people"></i> Anak Saya
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=orangtua/notifikasi" class="nav-link <?= ($activeMenu ?? '') == 'notifikasi' ? 'active' : '' ?>">
                                        <i class="bi bi-bell"></i> Notifikasi
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=quran" class="nav-link <?= ($activeMenu ?? '') == 'quran' ? 'active' : '' ?>">
                                        <i class="bi bi-book"></i> Baca Quran
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a href="index.php?action=tajweed-guide" class="nav-link <?= ($activeMenu ?? '') == 'tajweed_guide' ? 'active' : '' ?>">
                                        <i class="bi bi-palette"></i> Panduan Tajwid
                                    </a>
                                </li>
                                <li class="nav-item"><a href="index.php?action=bookmark" class="nav-link"><i class="bi bi-bookmark"></i> Bookmark</a></li>
                                <li class="nav-item"><a href="index.php?action=statistics/weekly" class="nav-link"><i class="bi bi-graph-up"></i> Statistik Hafalan</a></li>
                                <li class="nav-item">
                                    <a href="index.php?action=profile" class="nav-link <?= ($activeMenu ?? '') == 'profile' ? 'active' : '' ?>">
                                        <i class="bi bi-gear"></i> Pengaturan
                                    </a>
                                </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a href="index.php?action=reports" class="nav-link">
                                    <i class="bi bi-file-text"></i> Laporan
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-md-9">
                <?php else: ?>
                    <div class="col-12">
                    <?php endif; ?>

                    <!-- Konten dinamis dari child view -->
                    <?= $content ?? '<div class="alert alert-warning">Konten tidak tersedia.</div>' ?>

                    </div>
                </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>