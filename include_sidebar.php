<!-- Sidebar -->
<div class="sidebar" style="background-color: #000 !important;">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" style="background-color: #000 !important;">

            <a href="index.php" class="logo text-white fw-bold">
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
                <?php if($_SESSION['role_id'] == 1): ?>
                <li class="nav-item <?php echo ($active === 'admin') ? 'active' : ''; ?>">
                    <a href="adminpanel.php" class="text-white">
                        <i class="fas fa-home"></i>
                        <p>Admin Panel</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-item <?php echo ($active === 'index') ? 'active' : ''; ?>">
                    <a href="index.php" class="text-white">
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
                <?php if($_SESSION['role_id'] == 1): ?>
                <li class="nav-item <?php echo ($active === 'inventory') ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#base" class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Inventory Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="base">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="stock.php">
                                    <span class="sub-item text-white">Stock</span>
                                </a>
                            </li>
                            <li>
                                <a href="categories.php">
                                    <span class="sub-item text-white">Categories</span>
                                </a>
                            </li>
                            
                            <li>
                                <a href="sizes.php">
                                    <span class="sub-item text-white">Sizes</span>
                                </a>
                            </li>
                            <li>
                                <a href="suppliers.php">
                                    <span class="sub-item text-white">Suppliers</span>
                                </a>
                            </li>
                            <li>
                                <a href="brands.php">
                                    <span class="sub-item text-white">Brands</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <li class="nav-item <?php echo ($active === 'account') ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#acc"  class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Account Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="acc">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="staff.php">
                                    <span class="sub-item text-white">Staff</span>
                                </a>
                            </li>
                            <?php if($_SESSION['role_id'] == 1):?>
                            <li>
                                <a href="supplieraccount.php">
                                    <span class="sub-item text-white">Supplier</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li>
                                <a href="branches.php">
                                    <span class="sub-item text-white">Branches</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <li class="nav-item <?php echo ($active === 'expenses') ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#expenses"  class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Expenses Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="expenses">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="adminorderhistory.php">
                                    <span class="sub-item text-white">Order History</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                <li class="nav-item <?php echo ($active === 'sales') ? 'active' : ''; ?>">
                    <a data-bs-toggle="collapse" href="#sales"  class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Sales Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="sales">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="purchase.php">
                                    <span class="sub-item text-white">Purchases</span>
                                </a>
                            </li>
                            <li>
                                <a href="report.php">
                                    <span class="sub-item text-white">Report</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->