<!-- Sidebar -->
<div class="sidebar" style="background-color: <?php if($_SESSION['role_id'] == 1) echo "#000"; else if ($_SESSION['role_id'] == 2) echo "#4766c4";?> !important;">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header" style="background-color: <?php if($_SESSION['role_id'] == 1) echo "#000"; else if ($_SESSION['role_id'] == 2) echo "#4766c4";?> !important;">

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
                <li class="nav-item ">
                    <a href="<?php if($_SESSION['role_id'] == 2):?>index.php<?php else: ?>adminpanel.php<?php endif; ?>" class="text-white">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <?php if($_SESSION['role_id'] == 1):?>
                <li class="nav-item">
                    <a href="branches.php" class="text-white">
                        <i class="fas fa-home"></i>
                        <p>Branches</p>
                    </a>
                </li>
                <?php endif; ?>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Menu</h4>
                </li>
                <?php if($_SESSION['role_id'] == 1): ?>
                <li class="nav-item">
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
                            <li>
                                <a href="loginhistory.php">
                                    <span class="sub-item text-white">Login History</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                <li class="nav-item ">
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
                            <?php if($_SESSION['role_id'] == 1):?>
                            <li>
                                <a href="archive.php">
                                    <span class="sub-item text-white">Archive</span>
                                </a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </li>
                

                <li class="nav-item ">
                    <a data-bs-toggle="collapse" href="#expenses"  class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Supply Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="expenses">
                        <ul class="nav nav-collapse">
                            <?php if($_SESSION['role_id'] == 1):?>
                            <li>
                                <a href="stock_admin.php">
                                    <span class="sub-item text-white">Main Stock</span>
                                </a>
                            </li>
                            <li>
                                <a href="stock_requests.php">
                                    <span class="sub-item text-white">Supply Chain Status</span>
                                </a>
                            </li>
                            <li>
                                <a href="adminorderhistory.php">
                                    <span class="sub-item text-white">Stock Request</span>
                                </a>
                            </li>
                            <?php else: ?>
                            <li>
                                <a href="request_stock.php">
                                    <span class="sub-item text-white">Request Stock</span>
                                </a>
                            </li>
                            <?php endif; ?>
                            
                        </ul>
                    </div>
                </li>
                <?php if($_SESSION['role_id'] == 1):?>
                <li class="nav-item ">
                    <a data-bs-toggle="collapse" href="#expenses2"  class="text-white">
                        <i class="fas fa-layer-group"></i>
                        <p>Expense Management</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse" id="expenses2">
                        <ul class="nav nav-collapse">
                            
                            <li>
                                <a href="expenses.php">
                                    <span class="sub-item text-white">Expenses</span>
                                </a>
                            </li>                 
                            
                        </ul>
                    </div>
                </li>
                <?php endif; ?>
                
                
                <li class="nav-item ">
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