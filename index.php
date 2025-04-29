<?php 
	require ('session.php');
	require ('db.php');

	if(isset($_SESSION['role_id']) && $_SESSION['role_id'] == 1){
		header('Location: adminpanel.php');
	}

	$sql = "SELECT sum(stock) as total_quantity FROM items WHERE branch_id = :branch_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

	if ($result['total_quantity'] === null) {
        $quantity = 0;
    }
	else if($result['total_quantity'] > 999999){
		$quantity = number_format(999999) . "+";
	}
	else{
		$quantity = number_format($result['total_quantity']);
	}

	$sql1 = "SELECT sum(price) as total_sales, COUNT(purchase_id) as orders FROM purchases WHERE (DATE(date) = CURDATE()) AND branch_id = :branch_id";
	$stmt1 = $conn->prepare($sql1);
	$stmt1->bindParam(":branch_id", $_SESSION['branch_id']);
	$stmt1->execute();
	$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Dashboard </title>
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
	<style>
		.card1 {
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}

		.card1:hover {
			transform: scale(1.05);
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
			background-color: #f8f9fa;
		}

	</style>

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
						<li class="nav-item active">
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
							<a data-bs-toggle="collapse" href="#base">
								<i class="fas fa-layer-group"></i>
								<p>Inventory Management</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="base">
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
									<li>
										<a href="branches.php">
											<span class="sub-item">Branches</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item">
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
							<!-- notif area -->
							<?php 
								if(isset($_SESSION['user_id'])){
									$sql = "SELECT username, email, firstname, lastname, created_at FROM users JOIN userdetails ON users.user_id = userdetails.user_id WHERE users.user_id = :user_id";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(":user_id", $_SESSION['user_id']);
									$stmt->execute();
									$user_data = $stmt->fetch(PDO::FETCH_ASSOC);
									if($user_data) {
										$username = $user_data['username'];
										$email = $user_data['email'];
										$firstname = $user_data['firstname'];
										$lastname = $user_data['lastname'];
										$fullname = $firstname . " " . $lastname;
										$created_at = $user_data['created_at'];
									} else {
										$username = "User not found.";
										$email = "User not found.";
									}
								}

								if (isset($_GET['notif_id'])) {
									$notif_id = $_GET['notif_id'];
								
									$sql = "UPDATE notifications SET seen = 1 WHERE notification_id = :notif_id";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':notif_id', $notif_id, PDO::PARAM_INT);
									$stmt->execute();

									$sql = "SELECT target_url FROM notifications WHERE notification_id = :notif_id";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':notif_id', $notif_id, PDO::PARAM_INT);
									$stmt->execute();
									$notif = $stmt->fetch(PDO::FETCH_ASSOC);
									if ($notif && isset($notif['target_url'])) {
										$target_url = $notif['target_url'];
										echo "<script>window.location = '$target_url';</script>";
										exit();
									}
								}
							
								$sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 5";
								$stmt = $conn->prepare($sql);
								$stmt->bindParam(':user_id', $_SESSION['user_id']);
								$stmt->execute();
								$notifications = $stmt->fetchAll();
								$notif_count = count(array_filter($notifications, function($notif) {
									return $notif['seen'] == 0;
								}));

								function timeAgo($datetime) {
									date_default_timezone_set('Asia/Manila');
								
									$timestamp = strtotime($datetime);
									if (!$timestamp || $timestamp > time()) return 'just now';
								
									$diff = time() - $timestamp;
								
									$units = [
										'year'   => 31536000,
										'month'  => 2592000,
										'week'   => 604800,
										'day'    => 86400,
										'hour'   => 3600,
										'minute' => 60,
										'second' => 1
									];
								
									foreach ($units as $unit => $seconds) {
										$value = floor($diff / $seconds);
										if ($value >= 1) {
											$label = $value == 1 ? $unit : $unit . 's';
											return "$value $label ago";
										}
									}
								
									return 'just now';
								}
								
							?>
							<li class="nav-item topbar-icon dropdown hidden-caret">
								<a
									class="nav-link dropdown-toggle"
									href="#"
									id="notifDropdown"
									role="button"
									data-bs-toggle="dropdown"
									aria-haspopup="true"
									aria-expanded="false"
								>
                    				<i class="fa fa-bell"></i>
                    				<?php if ($notif_count > 0): ?>
										<span class="notification"><?= $notif_count ?></span>
									<?php endif; ?>
                  				</a>
                  				<ul class="dropdown-menu notif-box animated fadeIn" aria-labelledby="notifDropdown">
                    				<li>
                      					<div class="dropdown-title">You have <?= $notif_count ?> new notification</div>
                    				</li>
                    				<li>
                      					<div class="notif-scroll scrollbar-outer">
                        					<div class="notif-center">
												<?php foreach($notifications as $row):?>
													<a href="?notif_id=<?php echo $row['notification_id']; ?>" class="<?= $row['seen'] == 0 ? 'bg-light' : '' ?> p-2 rounded">
														<div class="notif-icon notif-primary flex-shrink-0" style="width: 30px; height: 30px; display: flex; align-items: center; justify-content: center;">
															<i class="bi <?php echo $row['icon']?>"></i>
														</div>
														<div class="notif-content">
															<span class="block"> <?php echo $row['message']?> </span>
															<span class="time"><?= timeAgo($row['created_at']) ?></span>
														</div>
													</a>
												<?php endforeach; ?>
                        					</div>
                      					</div>
                    				</li>
                    				<li>
                      					<a class="see-all" href="javascript:void(0);">See all notifications<i class="fa fa-angle-right"></i>
                      					</a>
                    				</li>
                  				</ul>
                			</li>
							<li class="nav-item topbar-user dropdown hidden-caret">
								<a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
									<div class="avatar-sm">
										<img src="assets/img/profile.png" alt="..." class="avatar-img rounded-circle">
									</div>
									<span class="profile-username">
										<span class="op-7">Hi,</span> <span class="fw-bold"><?php echo $username?></span>
									</span>
								</a>
								<ul class="dropdown-menu dropdown-user animated fadeIn">
									<div class="dropdown-user-scroll scrollbar-outer">
										<li>
											<div class="user-box">
												<div class="avatar-lg"><img src="assets/img/profile.png" alt="image profile" class="avatar-img rounded"></div>
												<div class="u-text">
													<h4><?php echo $username?></h4>
													<p class="text-muted"><?php echo $email?></p><a href="#" class="btn btn-xs btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a>
												</div>
											</div>
										</li>
										<li>
											<?php if($_SESSION['user_id'] == 17):?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="adminportal.php">Change Branch</a>
											<?php endif; ?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" id="accountSetting" data-bs-toggle="modal" data-bs-target="#editAccountModal" data-id="<?php if(isset($_SESSION['user_id'])){echo $_SESSION['user_id'];}  ?>">Account Setting</a>
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
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
						<div>
							<?php 
							$sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
							$stmt->execute();
							$branch_name = $stmt->fetchColumn();
							?>
							<h3 class="fw-bold mb-3"><?php echo $branch_name; ?></h3>
						</div>
						<!--
						<div class="ms-md-auto py-2 py-md-0">
							<a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
							<a href="#" class="btn btn-primary btn-round">Add Customer</a>
						</div> 
						-->
					</div>
					<div class="row">
						<div class="col-sm-6 col-md-4">
							<a href="purchase.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-success bubble-shadow-small">
													<i class="fas fa-luggage-cart"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers w-100">
													<p class="card-category">Sales Today</p>
													<h4 class="card-title">₱<?php echo number_format($result1['total_sales'], 2) ?></h4>
													
												</div>
												<span id="percentageText" class="text-muted float-end"></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-4">
							<a href="stock.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-primary bubble-shadow-small">
													<i class="bi bi-box-fill"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers">
													<p class="card-category">Stock</p>
													<h4 class="card-title"><?php echo $quantity?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-4">
							<a href="purchase.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-secondary bubble-shadow-small">
													<i class="fa fa-book"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers">
													<p class="card-category">Orders Today</p>
													<h4 class="card-title"><?php echo $result1['orders']?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						
						
						<!-- FETCH -->
						<?php
							$sql = "SELECT item_name, stock, size_name FROM items JOIN sizes ON items.size_id = sizes.size_id WHERE items.branch_id = :branch_id";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
							$stmt->execute();
							$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
							
							$itemNames = [];
							$itemStocks = [];
							$colors = [];
							
							$lowStockThreshold = 10;
							
							foreach ($items as $item) {
								$itemNames[] = $item['item_name'].' '.$item['size_name'];
								$itemStocks[] = $item['stock'];
								
								if ($item['stock'] < $lowStockThreshold) {
									$colors[] = 'rgb(255, 99, 71)'; 
								} else {
									$colors[] = 'rgb(34, 193, 34)';
								}
							}

							$sql = "SELECT DATE(date) AS day, SUM(price) AS total_price
							FROM purchases
							WHERE branch_id = :branch_id
							GROUP BY DATE(date)
							ORDER BY day ASC
							";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
							$stmt->execute();
							$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

							$labels = [];
							$values = [];
							foreach ($sales as $row) {
								$labels[] = date("M d, Y", strtotime($row['day']));
								$values[] = (float) $row['total_price'];
							}
						?>
						<div class="row">
							<div class="col-md-6 d-flex flex-column">
								<div class="card h-100">
									<div class="card-header">
										<div class="card-title">Sales Overview</div>
									</div>
									<div class="card-body">
										<div class="chart-container mb-5">
											<canvas id="sales_chart"></canvas>
										</div>
										<div class="row mb-3">
											<div class="col">
												<label for="startDate">Start Date</label>
												<input type="date" id="startDate" class="form-control">
											</div>
											<div class="col">
												<label for="endDate">End Date</label>
												<input type="date" id="endDate" class="form-control">
											</div>
										</div>
									</div>
								</div>
								
							</div>

							<div class="col-md-6 d-flex flex-column">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Stock Overview</div>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="items_chart"></canvas>
										</div>
										<div class="dropdown mb-3">
											<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
												Filter Items
											</button>
											<ul class="dropdown-menu" id="itemFilterList">
												<?php foreach ($itemNames as $index => $item): ?>
													<li>
														<label class="dropdown-item">
															<input type="checkbox" class="form-check-input me-1 item-filter" value="<?= $index ?>" checked>
															<?= htmlspecialchars($item) ?>
														</label>
													</li>
												<?php endforeach; ?>
											</ul>
										</div>
									</div>
								</div>
								<?php 
									$sql = "SELECT purchase_id, price, date, payment_method 
									FROM purchases p1 
									JOIN payment_method p2 ON p1.pm_id = p2.pm_id
									WHERE p1.branch_id = :branch_id
									ORDER BY p1.date DESC 
									LIMIT 1";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
									$stmt->execute();
									$recent_order = $stmt->fetch(PDO::FETCH_ASSOC);

									$sql = "SELECT * FROM notifications WHERE user_id = :user_id ORDER BY created_at DESC LIMIT 1";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':user_id', $_SESSION['user_id']);
									$stmt->execute();
									$recent_notif = $stmt->fetch(PDO::FETCH_ASSOC);

									$sql = "SELECT item_id, barcode, item_name, category_name, brand_name, supplier_name, size_name, price, stock
											FROM items i 
											JOIN categories c ON i.category_id = c.category_id
											JOIN brands b ON b.brand_id = i.brand_id
											JOIN suppliers s ON s.supplier_id = i.supplier_id
											JOIN sizes ss ON i.size_id = ss.size_id
											WHERE i.branch_id = :branch_id
											ORDER BY stock ASC
											LIMIT 1";
									$stmt = $conn->prepare($sql);
									$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
									$stmt->execute();
									$lowest_stock = $stmt->fetch(PDO::FETCH_ASSOC);
								?>
								<div class="card mt-auto">
									<div class="card-body">
										<h5 class="card-title mb-2">Recent Activity</h5>
										<div class="row">
											<div class="col-md-4">
												<ul>
													<li><strong>Last Login:</strong> <?php echo $_SESSION['last_login']?></li>
													<li>
														<strong>Recent Order:</strong>
														<?php if ($recent_order): ?>
															<span class="me-2">Order #<?php echo htmlspecialchars($recent_order['purchase_id']); ?>:</span>
															₱<span class="text-muted"><?php echo number_format($recent_order['price'], 2); ?></span>
														<?php else: ?>
															None
														<?php endif; ?>
													</li>
												</ul>
											</div>
											<div class="col-md-8">
												<ul>
													<?php if(!$_SESSION['user_id'] == 17): ?><li><strong>Recent Notification:</strong> <?php echo $recent_notif['message']?></li><?php endif; ?>
													<li>
														<strong>Lowest Stock Alert:</strong> 
														<?php if ($lowest_stock && $lowest_stock['stock'] <= 100): ?>
															<?php echo htmlspecialchars($lowest_stock['item_name']); ?>: 
															<?php echo number_format($lowest_stock['stock']); ?> stock left
														<?php else: echo 'None';?>
														<?php endif; ?>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
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
	
	<!-- Sweet Alert -->
	<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.0/dist/sweetalert2.all.min.js"></script>
	<?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Account Edited!',
                    text: 'The account has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the account.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
	<?php if(isset($_GET['access']) && $_GET['access'] == 'denied'): ?>
		<script>
			Swal.fire({
			icon: 'error',
			title: 'Access Denied',
			text: 'You do not have permission to view this page.',
			confirmButtonText: 'OK'
			});
		</script>
	<?php endif;?>
	
	<!--   Core JS Files   -->
	<script src="assets/js/core/jquery-3.7.1.min.js"></script>
	<script src="assets/js/core/popper.min.js"></script>
	<script src="assets/js/core/bootstrap.min.js"></script>

	<!-- jQuery Scrollbar -->
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<!-- jQuery Sparkline -->
	<script src="assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

	<!-- Chart Circle -->
	<script src="assets/js/plugin/chart-circle/circles.min.js"></script>

	<!-- Datatables -->
	<script src="assets/js/plugin/datatables/datatables.min.js"></script>

	<!-- Bootstrap Notify -->
	<script src="assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

	<!-- jQuery Vector Maps -->
	<script src="assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
	<script src="assets/js/plugin/jsvectormap/world.js"></script>

	

	<!-- Kaiadmin JS -->
	<script src="assets/js/kaiadmin.min.js"></script>

	<?php include 'modal_profile.php'?>
	<?php include 'modal_editaccount.php';?>

	<?php
		$sql = "SELECT item_name, stock, size_name FROM items JOIN sizes ON items.size_id = sizes.size_id WHERE items.branch_id = :branch_id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
		$stmt->execute();
		$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$itemNames = [];
		$itemStocks = [];
		$colors = [];
		
		$lowStockThreshold = 10;
		
		foreach ($items as $item) {
			$itemNames[] = $item['item_name'].' '.$item['size_name'];
			$itemStocks[] = $item['stock'];
			
			if ($item['stock'] < $lowStockThreshold) {
				$colors[] = 'rgb(255, 99, 71)'; 
			} else {
				$colors[] = 'rgb(34, 193, 34)';
			}
		}

		$sql = "SELECT DATE(date) AS day, SUM(price) AS total_price
        FROM purchases
		WHERE branch_id = :branch_id
        GROUP BY DATE(date)
        ORDER BY day ASC";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
		$stmt->execute();
		$sales = $stmt->fetchAll(PDO::FETCH_ASSOC);

		$labels = [];
		$values = [];
		foreach ($sales as $row) {
			$labels[] = date("M d, Y", strtotime($row['day']));
			$values[] = (float) $row['total_price'];
		}
	?>
		
	<script>
		var itemNames = <?php echo json_encode($itemNames); ?>;
		var itemStocks = <?php echo json_encode($itemStocks); ?>;
		var colors = <?php echo json_encode($colors); ?>;

		var labels = <?php echo json_encode($labels); ?>;
    	var values = <?php echo json_encode($values); ?>;
		
		var items_chart = document.getElementById("items_chart").getContext("2d");
		var sales_chart = document.getElementById("sales_chart").getContext("2d");
		
		var myItemsChart = new Chart(items_chart, {
			type: 'bar',
			data: {
				labels: itemNames,
				datasets: [{
					label: "Stock",
					backgroundColor: colors,
					borderColor: colors,
					data: itemStocks
				}],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
				scales: {
					yAxes: [{
						ticks: {
							beginAtZero: true
						}
					}]
				},
				legend: {
					display: false 
				}
			}
		});
		document.querySelectorAll(".item-filter").forEach(function (checkbox) {
			checkbox.addEventListener("change", function () {
				let selectedIndices = [];
				document.querySelectorAll(".item-filter:checked").forEach(cb => {
				selectedIndices.push(parseInt(cb.value));
				});

				const filteredLabels = selectedIndices.map(i => itemNames[i]);
				const filteredStocks = selectedIndices.map(i => itemStocks[i]);
				const filteredColors = selectedIndices.map(i => colors[i]);

				myItemsChart.data.labels = filteredLabels;
				myItemsChart.data.datasets[0].data = filteredStocks;
				myItemsChart.data.datasets[0].backgroundColor = filteredColors;
				myItemsChart.data.datasets[0].borderColor = filteredColors;

				myItemsChart.update();
			});
		});

		var mySalesChart = new Chart(sales_chart, {
			type: 'line',
			data: {
				labels: labels,
				datasets: [{
					label: 'Total Purchases',
					data: values,
					borderColor: 'rgb(255, 99, 132)',
					backgroundColor: 'rgba(255, 99, 132, 0.2)',
					lineTension: 0.1
				}]
			},
			options: {
				responsive: true,
				scales: {
					x: {
						title: {
							display: true,
							text: 'Date'
						}
					},
					y: {
						title: {
							display: true,
							text: 'Total Price'
						},
						beginAtZero: true
					}
				},
				legend: {
					display: false 
				}
			}
		});

		const originalLabels = <?php echo json_encode($labels); ?>;
		const originalValues = <?php echo json_encode($values); ?>;

		const currentDayValue = originalValues[originalValues.length - 1];

		const today = new Date();
		const currentDay = new Date(originalLabels[originalLabels.length - 1]);
		currentDay.setHours(0, 0, 0, 0);

		let percentageChange = 0;
		if (currentDay.getTime() === today.setHours(0, 0, 0, 0)) {
			const last7DaysValues = originalValues.slice(originalValues.length - 8, originalValues.length - 1);
			const totalSumOfLast7Days = last7DaysValues.reduce((acc, value) => acc + value, 0);
			const averageOfLast7Days = totalSumOfLast7Days / last7DaysValues.length;
			percentageChange = (((currentDayValue - averageOfLast7Days) / averageOfLast7Days) * 100).toFixed(2);

			if (percentageChange > 0) {
				percentageChange = '+' + percentageChange;
			}
		}

		document.getElementById("percentageText").textContent = percentageChange + "%";

		if (percentageChange > 0) {
			document.getElementById("percentageText").classList.add("text-success");
		} else if (percentageChange < 0) {
			document.getElementById("percentageText").classList.add("text-danger");
		} else {
			document.getElementById("percentageText").classList.add("text-muted");
		}

		document.getElementById("startDate").addEventListener("change", filterSalesChart);
		document.getElementById("endDate").addEventListener("change", filterSalesChart);

		function filterSalesChart() {
			const start = new Date(document.getElementById("startDate").value);
			const end = new Date(document.getElementById("endDate").value);

			start.setHours(0, 0, 0, 0);
			end.setHours(23, 59, 59, 999);

			const filteredLabels = [];
			const filteredValues = [];

			originalLabels.forEach((label, index) => {
				const current = new Date(label);
				current.setHours(0, 0, 0, 0);
				if ((!isNaN(start) && current < start) || (!isNaN(end) && current > end)) return;
				filteredLabels.push(label);
				filteredValues.push(originalValues[index]);
			});

			mySalesChart.data.labels = filteredLabels;
			mySalesChart.data.datasets[0].data = filteredValues;
			mySalesChart.update();
		}

	</script>
	<!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#accountSetting').on('click', function() {
                var userId = $(this).attr('data-id');
                $.ajax({
                    url: 'process_getaccountdata.php',
                    type: 'GET',
                    data: { id: userId },
                    dataType: 'json',
                    success: function(data) {
                        $('#editFirstName').val(data.firstname);
                        $('#editLastName').val(data.lastname);
                        $('#editUsername').val(data.username);
                        $('#editUserId').val(data.user_id);
						$('#editEmail').val(data.email);
						$('#editDestination').val('index.php');
                        $('#editAccountModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data: " + error);
                    }
                });
            });
        });
    </script>
</body>
</html>