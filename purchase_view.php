<?php 
	require ('session.php');
	require ('db.php');

    if(!isset($_GET['purchase_id'])){
        header('Location: purchase.php');
    }

	$sql = "SELECT i.item_name, pi.quantity, s.size_name, pi.quantity * i.price AS item_price FROM purchase_items pi
    JOIN items i ON i.item_id = pi.item_id
    JOIN sizes s ON i.size_id = s.size_id
    WHERE purchase_id = :purchase_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':purchase_id', $_GET['purchase_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Purchase Log</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/holicon.png" type="image/x-icon"/>

	<!-- Fonts and icons -->
	<script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Public Sans:300,400,500,600,700"]},
			custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>

	<!-- CSS Files -->
	<link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/plugins.min.css">
	<link rel="stylesheet" href="assets/css/kaiadmin.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header" data-background-color="dark">

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
						<li class="nav-item">
							<a href="index.php">
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
						<li class="nav-item">
							<a data-bs-toggle="collapse" href="#inv">
								<i class="fas fa-layer-group"></i>
								<p>Inventory Management</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="inv">
								<ul class="nav nav-collapse">
									<li>
										<a href="stock.php">
											<span class="sub-item">Stock</span>
										</a>
									</li>
									<li>
										<a href="categories.php">
											<span class="sub-item">Categories</span>
										</a>
									</li>
									
									<li>
										<a href="sizes.php">
											<span class="sub-item">Sizes</span>
										</a>
									</li>
									<li>
										<a href="suppliers.php">
											<span class="sub-item">Suppliers</span>
										</a>
									</li>
									<li>
										<a href="brands.php">
											<span class="sub-item">Brands</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
                        <li class="nav-item">
							<a data-bs-toggle="collapse" href="#acc">
								<i class="fas fa-layer-group"></i>
								<p>Account Management</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="acc">
								<ul class="nav nav-collapse">
									<li>
										<a href="staff.php">
											<span class="sub-item">Staff</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item active">
							<a data-bs-toggle="collapse" href="#sales">
								<i class="fas fa-layer-group"></i>
								<p>Sales Management</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sales">
								<ul class="nav nav-collapse">
									<li>
										<a href="purchase.php">
											<span class="sub-item">Purchase Log</span>
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

		<div class="main-panel">
			<div class="main-header">
				<div class="main-header-logo">
					<!-- Logo Header -->
					<div class="logo-header" data-background-color="dark">

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
				<!-- Navbar Header -->
				<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">

					<div class="container-fluid">
						<ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
							<li class="nav-item topbar-user dropdown hidden-caret">
								<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
									<div class="avatar-sm">
										<img src="assets/img/profile.png" alt="..." class="avatar-img rounded-circle">
									</div>
									<span class="profile-username">
										<span class="op-7">Hi,</span> <span class="fw-bold"><?php echo $_SESSION['username']?></span>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-user animated fadeIn">
									<div class="dropdown-user-scroll scrollbar-outer">
										<li>
											<div class="user-box">
												<div class="avatar-lg"><img src="assets/img/profile.png" alt="image profile" class="avatar-img rounded"></div>
												<div class="u-text">
													<h4><?php echo $_SESSION['username']?></h4>
													<p class="text-muted">hello@example.com</p><a href="profile.html" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
												</div>
											</div>
										</li>
										<li>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#">My Profile</a>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#">Account Setting</a>
											<div class="dropdown-divider"></div>
											<a id="logoutBtn" class="dropdown-item" href="#">Logout</a>
										</li>
                                        <script>
                                            document.getElementById('logoutBtn').addEventListener('click', function() {
                                                Swal.fire({
                                                    title: 'Are you sure?',
                                                    text: "Do you want to logout?",
                                                    icon: 'warning',
                                                    showCancelButton: true,
                                                    confirmButtonText: 'Yes, Logout!',
                                                    cancelButtonText: 'Cancel',
                                                    reverseButtons: true
                                                }).then((result) => {
                                                    if (result.isConfirmed) {
                                                        window.location.href = "logout.php";
                                                    }
                                                });
                                            });
                                        </script>
									</div>
								</ul>
							</li>
						</ul>
					</div>
				</nav>
				<!-- End Navbar -->
			</div>
			
			<div class="container">
                <div class="page-inner">
                    <div class="page-header">
						<h3 class="fw-bold mb-3">Purchase Log</h3>
						<ul class="breadcrumbs mb-3">
							<li class="nav-home">
								<a href="#">
									<i class="icon-home"></i>
								</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Sales Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="purchase.php">Purchase Log</a>
							</li>
                            <li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">View Purchase</a>
							</li>
						</ul>
					</div>
                    
                    <div class="card p-3">
                        <?php $totalPrice = 0;
                        foreach($data as $row){$totalPrice+=$row['item_price'];}?>
                        <h5 class="text-end" id="totalPrice">Total Price: ₱<?php echo number_format($totalPrice, 2); ?></h5>
                        <h5 class="mt-2 text-center fw-bold">House of Local</h5>
                        <div class="table-responsive">
                        <table class="table table-bordered" id="receipt">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Size</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($data as $row): ?>
                                    <tr>
                                        <td><?php echo $row['item_name']; ?></td>
                                        <td><?php echo $row['quantity']; ?></td>
                                        <td><?php echo $row['size_name']; ?></td>
                                        <td><?php echo $row['item_price']; ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                </div>
			</div>

			<footer class="footer">
				<div class="container-fluid">
					<nav class="pull-left">
						<ul class="nav">
							
						</ul>
					</nav>
					<div class="copyright ms-auto">
						
					</div>				
				</div>
			</footer>
		</div>
	</div>
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery-3.7.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>
	
	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>
	<!-- Kaiadmin JS -->
	<script src="assets/js/kaiadmin.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>
</body>
</html>