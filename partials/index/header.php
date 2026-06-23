<?php
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cloverania - Font Store</title>

    <link rel="stylesheet" href="/style/navigasi.css">
    <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/toastr.min.css">
    <link rel="stylesheet" href="/assets/datatables.min.css">
    <link rel="stylesheet" href="/font.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">

    <script src="/assets/jquery-3.6.1.js"></script>
    <script src="/assets/datatables.min.js"></script>
    <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js"></script>
    <script src="/assets/toastr.min.js"></script>
    <script src="/script.js"></script>

    <link rel="preload" href="/font/Open_Sans/OpenSans-Regular.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="/font/Open_Sans/OpenSans-Bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="/assets/bootstrap-icons/font/fonts/bootstrap-icons.woff2" as="font" type="font/woff2" crossorigin>

    <link rel="preload" href="/branding.webp" as="image">

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="shortcut icon" href="/favicon/favicon.ico" type="image/x-icon">
</head>

<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="sidebar" id="sidebar">
    <button class="sidebar-close" id="sidebarClose">&times;</button>

    <a href="/index.php" class="sidebar-brand">
        <img src="/branding.webp" alt="Cloverania Logo" width="36" height="36">
        Cloverania
    </a>

    <ul class="sidebar-nav">
        <li><a href="/index.php">Beranda</a></li>
        <li><a href="/daftar.php">Produk</a></li>
        <li><a href="/tentang.php">Tentang</a></li>
    </ul>

    <div class="sidebar-buttons">
        <a href="/login.php" class="btn btn-outline-secondary">CMS</a>
        <?php if (!isset($_SESSION['user_email'])): ?>
            <a href="/app/service/login-google.php" class="btn btn-outline-primary">
                <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512"><path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>Login Google
            </a>
        <?php else: ?>
            <div class="dropdown mb-2">
                <button class="btn btn-outline-primary w-100 dropdown-toggle" type="button" data-bs-toggle="dropdown" data-bs-boundary="viewport">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>Akun
                </button>
                <ul class="dropdown-menu w-100">
                    <li><h6 class="dropdown-header"><?= htmlspecialchars($_SESSION['user_name']) ?></h6></li>
                    <li><span class="dropdown-item text-muted small"><?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="/app/proses/google/logout-google.php"><svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M377.9 105.9L500.7 228.7c7.2 7.2 11.3 17.1 11.3 27.3s-4.1 20.1-11.3 27.3L377.9 406.1c-6.4 6.4-15 9.9-24 9.9c-18.7 0-33.9-15.2-33.9-33.9l0-62.1-128 0c-17.7 0-32-14.3-32-32l0-64c0-17.7 14.3-32 32-32l128 0 0-62.1c0-18.7 15.2-33.9 33.9-33.9c9 0 17.6 3.6 24 9.9zM160 96L96 96c-17.7 0-32 14.3-32 32l0 256c0 17.7 14.3 32 32 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32l-64 0c-53 0-96-43-96-96L0 128C0 75 43 32 96 32l64 0c17.7 0 32 14.3 32 32s-14.3 32-32 32z"/></svg>Logout</a></li>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</aside>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container">
        <a class="navbar-brand" href="/index.php">
            <img src="/branding.webp"
                 alt="Cloverania Logo"
                 width="36"
                 height="36"
                 loading="eager">
            Cloverania
        </a>

        <div class="collapse navbar-collapse d-none d-lg-block">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="/index.php">Beranda</a></li>
                <li class="nav-item"><a class="nav-link" href="/daftar.php">Produk</a></li>
                <li class="nav-item"><a class="nav-link" href="/tentang.php">Tentang</a></li>
            </ul>
        </div>

        <div class="navbar-actions d-none d-lg-flex">
            <a href="/login.php" class="btn btn-outline-secondary px-4">CMS</a>
            <?php if (!isset($_SESSION['user_email'])): ?>
                <a href="/app/service/login-google.php" class="btn btn-outline-primary px-4">
                    <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 488 512"><path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/></svg>Login Google
                </a>
            <?php else: ?>
                <div class="dropdown">
                    <button class="btn btn-outline-primary dropdown-toggle px-4" type="button" data-bs-toggle="dropdown">
                        <svg class="btn-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224 256A128 128 0 1 0 224 0a128 128 0 1 0 0 256zm-45.7 48C79.8 304 0 383.8 0 482.3C0 498.7 13.3 512 29.7 512l388.6 0c16.4 0 29.7-13.3 29.7-29.7C448 383.8 368.2 304 269.7 304l-91.4 0z"/></svg>Akun
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><h6 class="dropdown-header"><?= htmlspecialchars($_SESSION['user_name']) ?></h6></li>
                        <li><span class="dropdown-item text-muted small"><?= htmlspecialchars($_SESSION['user_email']) ?></span></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="/app/proses/google/logout-google.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></li>
                    </ul>
                </div>
            <?php endif; ?>
        </div>

        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" style="border: none; padding: 0.5rem;" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav>

<style>
    @media (max-width: 991px) {
        .navbar-brand {
            font-size: 1rem;
        }
        
        .navbar-brand img {
            width: 28px;
            height: 28px;
        }
        
        .navbar-toggler {
            margin-left: 0.5rem;
        }
    }
    
    @media (max-width: 576px) {
        .container {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .navbar-brand {
            font-size: 0.9rem;
        }
        
        .navbar-brand img {
            width: 24px;
            height: 24px;
        }
    }
</style>

<script>
    document.body.classList.add('loaded');

    const sidebar        = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    const sidebarToggle  = document.getElementById('sidebarToggle');
    const sidebarClose   = document.getElementById('sidebarClose');

    function syncClosePosition() {
        const rect = sidebarToggle.getBoundingClientRect();
        sidebarClose.style.top   = (rect.top + rect.height / 2) + 'px';
        sidebarClose.style.right = (window.innerWidth - rect.right) + 'px';
    }

    function openSidebar()  {
        syncClosePosition();
        sidebar.classList.add('active');
        sidebarOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar.classList.remove('active');
        sidebarOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    sidebarToggle.addEventListener('click', openSidebar);
    sidebarClose.addEventListener('click',  closeSidebar);
    sidebarOverlay.addEventListener('click', closeSidebar);

    window.addEventListener('resize', () => {
        if (sidebar.classList.contains('active')) syncClosePosition();
    });

    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') closeSidebar();
    });
</script>
