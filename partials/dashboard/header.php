<?php
$currentPage = basename($_SERVER['PHP_SELF']);

$pageModules = [
    'dashboard.php' => 'dashboard',
    'produk.php'    => 'produk',
    'edit.php'      => 'produk',
    'tambah.php'      => 'produk',   
    'user.php'      => 'user',
];

$moduleTitles = [
    'dashboard' => 'Dashboard',
    'produk'    => 'Kelola Produk',
    'user'      => 'Kelola Akses',
];

$activeMenu = $pageModules[$currentPage] ?? 'dashboard';
$pageTitle  = $moduleTitles[$activeMenu] ?? 'Dashboard';
?>
<!DOCTYPE html>
<html lang="id" data-bs-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?> - Cloverania</title>

 <link rel="stylesheet" href="/style/cms-header.css">
    <link rel="stylesheet" href="/assets/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="/assets/toastr.min.css">
    <link rel="stylesheet" href="/assets/datatables.min.css">
    <link rel="stylesheet" href="/font.css">
    <link rel="stylesheet" href="/style.css">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.css">

    <script src="/assets/jquery-3.6.1.js" defer></script>
    <script src="/assets/datatables.min.js" defer></script>
    <script src="/assets/bootstrap/dist/js/bootstrap.bundle.min.js" defer></script>
    <script src="/assets/toastr.min.js" defer></script>
    <script src="/script.js" defer></script>

    <link rel="apple-touch-icon" sizes="180x180" href="/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/favicon/favicon-16x16.png">
    <link rel="manifest" href="/favicon/site.webmanifest">
    <link rel="shortcut icon" href="/favicon/favicon.ico" type="image/x-icon">

    <link rel="preload" href="/font/Open_Sans/OpenSans-Regular.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="/font/Open_Sans/OpenSans-Bold.ttf" as="font" type="font/ttf" crossorigin>
    <link rel="preload" href="/assets/bootstrap-icons/font/fonts/bootstrap-icons.woff2" as="font" type="font/woff2" crossorigin>

    <script>
        (function () {
            try {
                var collapsed = localStorage.getItem('sidebarCollapsed') === '1';
                if (collapsed) {
                    document.documentElement.classList.add('sidebar-collapsed-init');
                }
            } catch (e) {}
        })();
    </script>
</head>

<body class="has-sidebar">
<noscript><style>body { opacity: 1 !important; }</style></noscript>

<nav class="navbar navbar-expand-lg navbar-dark fixed-top">
    <div class="container-fluid d-flex">
        <button class="navbar-toggler d-lg-none" type="button" id="sidebarToggle" style="border: none;" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <span class="navbar-page-title"><?php echo htmlspecialchars($pageTitle); ?></span>
    </div>
</nav>

<div class="page-wrapper">
