<!-- Sidebar -->
<div class="sidebar" style="background-color: #29b9d6 !important;">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" style="background-color: #29b9d6 !important;">

            <a href="supplier.php" class="logo text-dark fw-bold">
                <img src="assets/img/holicon.png" alt="navbar brand" class="navbar-brand" height="40">&nbsp;House of Local
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>

        </div>
        <!-- End Logo Header -->	
    </div>	
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <?php if($_SESSION['role_id'] == 1):?>
                <?php endif; ?>
                <li class="nav-item <?php echo ($active === 'dashboard') ? 'active' : ''; ?>">
                    <a href="supplier.php" class="text-dark">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <li class="nav-item <?php echo ($active === 'orders') ? 'active' : ''; ?>">
                    <a href="orderssupplier.php" class="text-dark">
                        <i class="fas fa-layer-group"></i>
                        <p>Orders</p>
                    </a>
                </li>

                <li class="nav-item <?php echo ($active === 'pricing') ? 'active' : ''; ?>">
                    <a href="pricingsupplier.php" class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Pricing</p>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->