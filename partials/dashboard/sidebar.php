<div class="sidebar-overlay" id="sidebarOverlay"></div>

<aside class="app-sidebar" id="appSidebar">

    <div class="app-sidebar-header">
        <a href="/dashboard.php" class="app-sidebar-brand">
            <img src="/branding.webp" alt="Cloverania" class="app-sidebar-brand-icon" width="20" height="20">
            <span class="app-sidebar-brand-text">Cloverania</span>
        </a>

        <button class="app-sidebar-collapse-btn" id="sidebarCollapseToggle" title="Collapse sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>

        <button class="app-sidebar-close-btn d-lg-none" id="sidebarClose" aria-label="Tutup menu">
            &times;
        </button>
    </div>

    <ul class="app-sidebar-nav">
        <li>
            <a href="/dashboard.php" class="<?php echo isset($activeMenu) && $activeMenu === 'dashboard' ? 'active' : ''; ?>" data-label="Dashboard">
                <i class="bi bi-speedometer2"></i>
                <span class="app-sidebar-link-text">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="/produk.php" class="<?php echo isset($activeMenu) && $activeMenu === 'produk' ? 'active' : ''; ?>" data-label="Kelola Produk">
                <i class="bi bi-box-seam"></i>
                <span class="app-sidebar-link-text">Kelola Produk</span>
            </a>
        </li>
        <li>
            <a href="/user.php" class="<?php echo isset($activeMenu) && $activeMenu === 'user' ? 'active' : ''; ?>" data-label="Kelola Akses">
                <i class="bi bi-people"></i>
                <span class="app-sidebar-link-text">Kelola Akses</span>
            </a>
        </li>
    </ul>

    <div class="app-sidebar-footer">
        <a href="/app/proses/login/logout.php" class="app-sidebar-logout" data-label="Logout">
            <i class="bi bi-box-arrow-right"></i>
            <span class="app-sidebar-link-text">Logout</span>
        </a>
    </div>

    <button class="app-sidebar-expand-btn" id="sidebarExpandToggle" title="Expand sidebar">
        <i class="bi bi-chevron-right"></i>
    </button>

</aside>

