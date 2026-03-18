<nav class="navbar navbar-top navbar-expand navbar-dashboard ps-0 pe-2 pb-0">
    <div class="container-fluid px-0">
        <div class="d-flex justify-content-between w-100 align-items-center" id="navbarSupportedContent">
            <div class="d-flex align-items-center">
                <span class="mt-1 ms-1 sidebar-text" style="font-family: var(--md-font); font-weight: 600; font-size: 16px; color: var(--md-on-surface);">Visitor Company</span>
            </div>
            <!-- Navbar links -->
            <ul class="navbar-nav align-items-center gap-1">
                <li class="nav-item dropdown">
                    <a class="nav-link notification-bell unread dropdown-toggle d-flex align-items-center justify-content-center" data-unread-notifications="true"
                        href="#" role="button" data-bs-toggle="dropdown" data-bs-display="static" aria-expanded="false"
                        style="width: 40px; height: 40px; border-radius: 50%; transition: background 0.15s ease;">
                        <i class="ti ti-bell" style="font-size: 20px; color: var(--md-on-surface-variant);"></i>
                    </a>
                </li>
                <li class="nav-item dropdown ms-lg-2 d-none d-lg-block">
                    <a class="nav-link dropdown-toggle pt-1 px-0 d-flex align-items-center gap-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <img class="rounded-circle" alt="User" src="<?php echo $_SESSION['VisitorMKT_image']; ?>"
                            style="width: 36px; height: 36px; object-fit: cover; border: 2px solid var(--md-outline-variant);">
                        <span style="font-size: 14px; font-weight: 500; color: var(--md-on-surface);"><?php echo $_SESSION['VisitorMKT_fullname']; ?></span>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end mt-2 py-1" style="min-width: 160px;">
                        <a class="dropdown-item d-flex align-items-center gap-2" href="#" style="color: var(--md-error);">
                            <i class="ti ti-logout" style="font-size: 18px;"></i>
                            Logout
                        </a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</nav>
