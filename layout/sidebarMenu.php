<script>
    const CHECK_INTERVAL = 10000;
    let alerted = false;

    function forceCloseTab() {
        const w = window.open(window.location.href, "_self");
        w.close();
    }

    function handleExpiredSession() {
        if (alerted) return;
        alerted = true;
        alert("หมดเวลาเชื่อมต่อแล้ว กรุณาเข้าใช้งานใหม่");
        forceCloseTab();
    }

    async function checkSession() {
        try {
            const response = await fetch('check_session_status.php');
            if (response.ok) {
                const data = await response.json();
                if (data.status === 'expired') {
                    handleExpiredSession();
                }
            }
        } catch (e) {
            console.error("session check failed:", e);
        }
    }

    ["click", "keypress", "scroll"].forEach(ev => {
        window.addEventListener(ev, checkSession);
    });

    setInterval(checkSession, CHECK_INTERVAL);
</script>

<nav id="sidebarMenu" class="sidebar d-lg-block collapse" data-simplebar>
    <div class="sidebar-inner px-3 pt-3">
        <!-- Mobile user card -->
        <div class="user-card d-flex d-md-none align-items-center justify-content-between justify-content-md-center pb-4">
            <div class="d-flex align-items-center">
                <div class="avatar-lg me-3">
                    <img src="<?php echo $_SESSION['VisitorMKT_image']; ?>" class="card-img-top rounded-circle border-white" alt="User">
                </div>
                <div class="d-block">
                    <h2 class="h6 mb-2" style="color: var(--md-on-surface);"><?php echo $_SESSION['VisitorMKT_name']; ?></h2>
                    <a href="#" class="btn btn-sm" style="background: var(--md-error-container); color: var(--md-error); border: none; border-radius: 20px; font-size: 12px;">
                        <i class="ti ti-logout me-1"></i> Sign Out
                    </a>
                </div>
            </div>
            <div class="collapse-close d-md-none">
                <a href="#sidebarMenu" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="true" aria-label="Toggle navigation">
                    <svg class="icon icon-xs" fill="var(--md-on-surface-variant)" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                    </svg>
                </a>
            </div>
        </div>

        <ul class="nav flex-column pt-3 pt-md-0">
            <!-- Logo -->
            <li class="nav-item d-none d-lg-block mb-3">
                <a href="./?page=dashboard" class="nav-link d-flex align-items-center" style="padding: 8px 12px !important;">
                    <span class="sidebar-icon">
                        <img src="https://innovation.asefa.co.th/cdn/logo/icon1.png" height="100%" width="100%" alt="Asefa Logo">
                    </span>
                </a>
            </li>

            <!-- Section label -->
            <li class="nav-item" style="padding: 8px 16px 4px;">
                <span style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: var(--md-on-surface-variant); opacity: 0.7;">Menu</span>
            </li>

            <!-- Dashboard -->
            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'dashboard' || !isset($_GET['page']) ? 'active' : ''; ?>">
                <a href="./?page=dashboard" class="nav-link">
                    <span class="sidebar-icon">
                        <i class="ti ti-layout-dashboard fs-5 me-2"></i>
                    </span>
                    <span class="sidebar-text">Dashboard</span>
                </a>
            </li>

            <!-- List Visitor -->
            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'listvisitor' ? 'active' : ''; ?>">
                <a href="./listvisitor.php?page=listvisitor" class="nav-link">
                    <span class="sidebar-icon">
                        <i class="ti ti-list fs-5 me-2"></i>
                    </span>
                    <span class="sidebar-text">List Visitor</span>
                </a>
            </li>

            <!-- Visitor Form -->
            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'insert' ? 'active' : ''; ?>">
                <a href="./visitorform.php?page=insert" class="nav-link">
                    <span class="sidebar-icon">
                        <i class="ti ti-forms fs-5 me-2"></i>
                    </span>
                    <span class="sidebar-text">Visitor Form</span>
                </a>
            </li>

            <!-- Related -->
            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'related' ? 'active' : ''; ?>">
                <a href="./related.php?page=related" class="nav-link">
                    <span class="sidebar-icon">
                        <i class="ti ti-clipboard-check fs-5 me-2"></i>
                    </span>
                    <span class="sidebar-text">ประเมินผู้เกี่ยวข้อง</span>
                </a>
            </li>

            <!-- Calendar (conditional) -->
            <?php if (in_array('Admin', $_SESSION['VisitorMKT_permision']) || in_array('Calendar', $_SESSION['VisitorMKT_permision'])) { ?>
            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'calendar' ? 'active' : ''; ?>">
                <a href="./calendar.php?page=calendar" class="nav-link">
                    <span class="sidebar-icon">
                        <i class="ti ti-calendar-event fs-5 me-2"></i>
                    </span>
                    <span class="sidebar-text">Calendar</span>
                </a>
            </li>
            <?php } ?>

            <!-- Settings (Admin only) -->
            <?php if (in_array('Admin', $_SESSION['VisitorMKT_permision'])) { ?>
                <li role="separator" style="height: 1px; background: var(--md-outline-variant); margin: 12px 16px;"></li>

                <!-- Section label -->
                <li class="nav-item" style="padding: 8px 16px 4px;">
                    <span style="font-size: 11px; font-weight: 600; letter-spacing: 0.5px; text-transform: uppercase; color: var(--md-on-surface-variant); opacity: 0.7;">Setting</span>
                </li>

                <li class="nav-item">
                    <span class="nav-link collapsed d-flex justify-content-between align-items-center" data-bs-toggle="collapse" data-bs-target="#submenu-components" <?php echo isset($_GET['page']) && ($_GET['page'] == 'permission' || $_GET['page'] == 'objective' || $_GET['page'] == 'groupcustomer' || $_GET['page'] == 'setfood' || $_GET['page'] == 'notigroup') ? 'aria-expanded="true"' : 'aria-expanded="false"'; ?>>
                        <span>
                            <span class="sidebar-icon">
                                <i class="ti ti-settings fs-5 me-2"></i>
                            </span>
                            <span class="sidebar-text">Setting</span>
                        </span>
                        <span class="link-arrow">
                            <svg class="icon icon-sm" fill="currentColor" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path>
                            </svg>
                        </span>
                    </span>
                    <div class="multi-level collapse <?php echo isset($_GET['page']) && ($_GET['page'] == 'permission' || $_GET['page'] == 'objective' || $_GET['page'] == 'groupcustomer' || $_GET['page'] == 'setfood' || $_GET['page'] == 'notigroup') ? 'show' : ''; ?>" role="list" id="submenu-components" aria-expanded="false">
                        <ul class="flex-column nav">
                            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'objective' ? 'active' : ''; ?>">
                                <a class="nav-link" href="./objective.php?page=objective">
                                    <span class="sidebar-text"><i class="ti ti-target me-2 fs-5"></i>วัตถุประสงค์</span>
                                </a>
                            </li>
                            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'groupcustomer' ? 'active' : ''; ?>">
                                <a class="nav-link" href="./groupcustomer.php?page=groupcustomer">
                                    <span class="sidebar-text"><i class="ti ti-users-group me-2 fs-5"></i>กลุ่มลูกค้า</span>
                                </a>
                            </li>
                            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'setfood' ? 'active' : ''; ?>">
                                <a class="nav-link" href="./setfood.php?page=setfood">
                                    <span class="sidebar-text"><i class="ti ti-tools-kitchen-3 me-2 fs-5"></i>ชุดอาหาร</span>
                                </a>
                            </li>
                            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'notigroup' ? 'active' : ''; ?>">
                                <a class="nav-link" href="./notigroup.php?page=notigroup">
                                    <span class="sidebar-text"><i class="ti ti-bell-check me-2 fs-5"></i>กลุ่มแจ้งเตือน</span>
                                </a>
                            </li>
                            <li class="nav-item <?php echo isset($_GET['page']) && $_GET['page'] == 'permission' ? 'active' : ''; ?>">
                                <a class="nav-link" href="./permission.php?page=permission">
                                    <span class="sidebar-text"><i class="ti ti-user-cog me-2 fs-5"></i>สิทธิ์เข้าใช้</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
            <?php } ?>
        </ul>
    </div>
</nav>
