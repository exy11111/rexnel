<?php 
	require ('session.php');
	require ('db.php');

	$sql = "SELECT category_id, category_name FROM categories WHERE branch_id = :branch_id";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT purchase_id, price, date, payment_method FROM purchases p1 JOIN payment_method p2 ON p1.pm_id = p2.pm_id WHERE p1.branch_id = :branch_id ORDER BY p1.date DESC";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Report</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/holicon.png" type="image/x-icon"/>
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

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

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
	<style>
		.card1 {
			transition: transform 0.3s ease, box-shadow 0.3s ease;
		}

		.card1:hover {
			transform: scale(1.05);
			box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
			background-color: #f8f9fa;
		}.nav-item.active a {
			color: #fff !important;
		}

	</style>
</head>
<body>
	<div class="wrapper">
		<?php $active = 'sales';?>
		<?php include ('include_sidebar.php'); ?>

		<div class="main-panel">
			<?php include ('include_navbar.php'); ?>
			
			<div class="container" id="pdf">
				<div class="page-inner">
					<div class="page-header d-flex justify-content-between align-items-center">
						<div class="d-flex align-items-center gap-3">
							<?php 
							$sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
							$stmt->execute();
							$branch_name = $stmt->fetchColumn();
							?>
							<h3 class="fw-bold mb-0"><?php echo $branch_name; ?></h3>
							<ul class="breadcrumbs mb-0 d-flex align-items-center">
								<li class="nav-home">
									<a href="index.php">
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
									<a href="#">Report</a>
								</li>
							</ul>
						</div>

						<div>
							<button class="btn btn-primary" id="downloadPdfBtn">Download as PDF</button>
						</div>
					</div>

					<div class="row">
						<div class="col-sm-6 col-md-4">
							<a href="purchase.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-primary bubble-shadow-small">
													<i class="fas fa-shopping-bag"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers w-100">
													<p class="card-category">Revenue</p>
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
							<a href="purchase.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-danger bubble-shadow-small">
													<i class="fas fa-receipt"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers w-100">
													<p class="card-category">Expenses</p>
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
							<a href="purchase.php">
								<div class="card card1 card-stats card-round">
									<div class="card-body">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-success bubble-shadow-small">
													<i class="fas fa-chart-line"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers w-100">
													<p class="card-category">Profit</p>
													<h4 class="card-title">₱<?php echo number_format($result1['total_sales'], 2) ?></h4>
													
												</div>
												<span id="percentageText" class="text-muted float-end"></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
					</div>
					<div class="row">
						<?php 
							$today = date("Y-m-d");
							$sql = "SELECT SUM(pi.quantity) AS total_quantity
								FROM purchase_items pi
								INNER JOIN purchases p ON pi.purchase_id = p.purchase_id
								WHERE DATE(p.date) = :today";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':today', $today);
							$stmt->execute();

							$total_quantity_today = $stmt->fetchColumn() ?? 0;

							$thisMonth = date("Y-m");
							$sql = "SELECT SUM(pi.quantity) AS total_quantity
								FROM purchase_items pi
								INNER JOIN purchases p ON pi.purchase_id = p.purchase_id
								WHERE DATE_FORMAT(p.date, '%Y-%m') = :thisMonth";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':thisMonth', $thisMonth);
							$stmt->execute();

							$total_quantity_month = $stmt->fetchColumn() ?? 0;

							$thisYear = date("Y");
							$sql = "SELECT SUM(pi.quantity) AS total_quantity
								FROM purchase_items pi
								INNER JOIN purchases p ON pi.purchase_id = p.purchase_id
								WHERE YEAR(p.date) = :thisYear";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':thisYear', $thisYear);
							$stmt->execute();
							$total_quantity_year = $stmt->fetchColumn() ?? 0;

							$sql = "SELECT SUM(pi.quantity) AS total_quantity
								FROM purchase_items pi
								INNER JOIN purchases p ON pi.purchase_id = p.purchase_id";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							
							$total_quantity_alltime = $stmt->fetchColumn() ?? 0;

						?>
						<div class="col-sm-6 col-md-3">
							<a href="#">
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
													<p class="card-category">Daily Items Sold</p>
													<h4 class="card-title"><?php echo $total_quantity_today ?></h4>
												</div>
												<span id="percentageText" class="text-muted float-end"></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-3">
							<a href="#">
								<div class="card card1 card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-primary bubble-shadow-small">
													<i class="bi bi-calendar-month"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers">
													<p class="card-category">Monthly Items Sold</p>
													<h4 class="card-title"><?php echo $total_quantity_month ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-3">
							<a href="#">
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
													<p class="card-category">Yearly Items Sold</p>
													<h4 class="card-title"><?php echo $total_quantity_year ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-3">
							<a href="#">
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
													<p class="card-category">All Time Items Sold</p>
													<h4 class="card-title"><?php echo $total_quantity_alltime ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
					</div>
					<div class="row">
						<?php 
							$today = date("Y-m-d");
							$sql = "SELECT SUM(price) AS daily_sales
								FROM purchases
								WHERE DATE(date) = :today";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':today', $today);
							$stmt->execute();

							$daily_sales = $stmt->fetchColumn();

							$thisMonth = date("Y-m");
							$sql = "SELECT SUM(price) AS monthly_sales
								FROM purchases
								WHERE DATE_FORMAT(`date`, '%Y-%m') = :thisMonth";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':thisMonth', $thisMonth);
							$stmt->execute();

							$monthly_sales = $stmt->fetchColumn();

							$thisYear = date("Y");
							$sql = "SELECT SUM(price) AS yearly_sales
							FROM purchases
							WHERE YEAR(`date`) = :thisYear";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':thisYear', $thisYear);
							$stmt->execute();
							$yearly_sales = $stmt->fetchColumn();


						?>
						<div class="col-sm-6 col-md-4">
							<a href="#">
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
													<p class="card-category">Daily Sales</p>
													<h4 class="card-title">₱<?php echo number_format($daily_sales, 2) ?></h4>
												</div>
												<span id="percentageText" class="text-muted float-end"></span>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-4">
							<a href="#">
								<div class="card card1 card-stats card-round">
									<div class="card-body ">
										<div class="row align-items-center">
											<div class="col-icon">
												<div class="icon-big text-center icon-primary bubble-shadow-small">
													<i class="bi bi-calendar-month"></i>
												</div>
											</div>
											<div class="col col-stats ms-3 ms-sm-0">
												<div class="numbers">
													<p class="card-category">Monthly Sales</p>
													<h4 class="card-title">₱<?php echo number_format($monthly_sales, 2) ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						<div class="col-sm-6 col-md-4">
							<a href="#">
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
													<p class="card-category">Yearly Sales</p>
													<h4 class="card-title">₱<?php echo number_format($yearly_sales, 2) ?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
						
						
						
					</div>

					<div class="row d-flex align-items-stretch">
						<div class="col-sm-12 col-md-6 d-flex">
							<div class="card flex-fill">
								<div class="card-header d-flex justify-content-between align-items-center">
									<h4 class="card-title mb-0">Top Product Sales</h4>
									<?php
										$selectedFilter = isset($_GET['top_product_filter']) ? $_GET['top_product_filter'] : 'all';

										switch ($selectedFilter) {
											case 'today':
												$filterLabel = "Today";
												break;
											case 'month':
												$filterLabel = "This Month";
												break;
											case 'year':
												$filterLabel = "This Year";
												break;
											default:
												$filterLabel = "All Time";
												break;
										}
									?>
									<div class="d-flex align-items-center">
										<div class="dropdown">
											<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
												<?= htmlspecialchars($filterLabel) ?>
											</button>
											<?php
												$queryParams = $_GET;

												$productFilters = [
													'today' => 'Today',
													'month' => 'This Month',
													'year'  => 'This Year',
													'all'   => 'All Time'
												];
											?>
											<ul class="dropdown-menu" aria-labelledby="filterDropdown">
												<?php foreach ($productFilters as $key => $label): ?>
													<?php
														$params = $queryParams;
														$params['top_product_filter'] = $key;
														$url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($params);
													?>
													<li><a class="dropdown-item" href="<?= $url ?>"><?= $label ?></a></li>
												<?php endforeach; ?>
											</ul>
										</div>
										<button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#viewAllModal">
											View All
										</button>
									</div>
									
								</div>

								<?php 
									$topProductFilter = isset($_GET['top_product_filter']) ? $_GET['top_product_filter'] : 'all';

									$where = "WHERE p.branch_id = :branch_id";
									$params = [];
									$params[':branch_id'] = $_SESSION['branch_id'];
									
									$today = date('Y-m-d');
									$thisMonth = date('m');
									$thisYear  = date('Y');

									if ($topProductFilter === 'today') {
										$where .= " AND DATE(p.date) = :today";
										$params[':today'] = $today;
									} elseif ($topProductFilter === 'month') {
										$where .= " AND YEAR(p.date) = :year AND MONTH(p.date) = :month";
										$params[':year']  = $thisYear;
										$params[':month'] = $thisMonth;
									} elseif ($topProductFilter === 'year') {
										$where .= " AND YEAR(p.date) = :year";
										$params[':year'] = $thisYear;
									}							

									$sql = "SELECT 
												i.item_id,
												i.item_name,
												i.price,
												SUM(pi.quantity) AS total_sold,
												SUM(pi.quantity * i.price) AS total_revenue
											FROM purchase_items pi
											JOIN items i ON pi.item_id = i.item_id
											JOIN purchases p ON pi.purchase_id = p.purchase_id
											$where
											GROUP BY i.item_id, i.item_name, i.price
											ORDER BY total_sold DESC";

									$stmt = $conn->prepare($sql);
									$stmt->execute($params);
									$topProducts = $stmt->fetchAll(PDO::FETCH_ASSOC);

								?>
								<div class="card-body">
								<?php 
									$counter = 0;
									foreach ($topProducts as $row): 
										if ($counter >= 3) break;
									?>
										<div class="card mb-3">
											<div class="card-body d-flex justify-content-between align-items-center">
												<div>
													<h5 class="card-title mb-1"><?php echo $row['item_name'];?></h5>
													<p class="mb-1">Price: ₱<?php echo number_format($row['price'], 2);?></p>
												</div>
												<div class="flex-grow-1 text-center">
													<p class="mb-0 fw-bold">Sold: <?php echo $row['total_sold'];?></p>
												</div>
												<a href="stock.php" class="btn btn-primary btn-sm">View</a>
											</div>
										</div>
									<?php 
										$counter++;
									endforeach; 
									?>
								</div>
							</div>
						</div>
						<?php include 'modal_viewallproduct.php'?>


						<div class="col-sm-12 col-md-6 d-flex">
							<div class="card flex-fill">
								<div class="card-header d-flex justify-content-between align-items-center">
									<h4 class="card-title mb-0">Top Brand Sales</h4>
									<?php
										$selectedFilterBrand = isset($_GET['top_brand_filter']) ? $_GET['top_brand_filter'] : 'all';

										switch ($selectedFilterBrand) {
											case 'today':
												$filterLabelBrand = "Today";
												break;
											case 'month':
												$filterLabelBrand = "This Month";
												break;
											case 'year':
												$filterLabelBrand = "This Year";
												break;
											default:
												$filterLabelBrand = "All Time";
												break;
										}
									?>
									<div class="d-flex align-items-center">
										<div class="dropdown">
											<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdownBrand" data-bs-toggle="dropdown" aria-expanded="false">
												<?= htmlspecialchars($filterLabelBrand) ?>
											</button>
											<ul class="dropdown-menu" aria-labelledby="filterDropdownBrand">
												<?php foreach ($brandFilters as $key => $label): ?>
													<?php
														$params = $queryParams;
														$params['top_brand_filter'] = $key;
														$url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($params);
													?>
													<li><a class="dropdown-item" href="<?= $url ?>"><?= $label ?></a></li>
												<?php endforeach; ?>
											</ul>
										</div>
										<button class="btn btn-sm btn-primary ms-2" data-bs-toggle="modal" data-bs-target="#viewAllBrandsModal">
											View All
										</button>
									</div>
								</div>

								<?php 
									$topBrandFilter = isset($_GET['top_brand_filter']) ? $_GET['top_brand_filter'] : 'all';

									$whereBrand = "WHERE p.branch_id = :branch_id";
									$paramsBrand = [':branch_id' => $_SESSION['branch_id']];
									
									$today = date('Y-m-d');
									$thisMonth = date('m');
									$thisYear  = date('Y');
									
									if ($topBrandFilter === 'today') {
										$whereBrand .= " AND DATE(p.date) = :today";
										$paramsBrand[':today'] = $today;
									} elseif ($topBrandFilter === 'month') {
										$whereBrand .= " AND YEAR(p.date) = :year AND MONTH(p.date) = :month";
										$paramsBrand[':year']  = $thisYear;
										$paramsBrand[':month'] = $thisMonth;
									} elseif ($topBrandFilter === 'year') {
										$whereBrand .= " AND YEAR(p.date) = :year";
										$paramsBrand[':year'] = $thisYear;
									}					

									$sql = "SELECT 
										b.brand_id,
										b.brand_name,
										SUM(pi.quantity) AS total_sold,
										SUM(pi.quantity * i.price) AS total_revenue
									FROM purchase_items pi
									JOIN items i ON pi.item_id = i.item_id
									JOIN purchases p ON pi.purchase_id = p.purchase_id
									JOIN brands b ON i.brand_id = b.brand_id
									$whereBrand
									GROUP BY b.brand_id, b.brand_name
									ORDER BY total_sold DESC";

									$stmt = $conn->prepare($sql);
									$stmt->execute($paramsBrand);
									$topBrands = $stmt->fetchAll(PDO::FETCH_ASSOC);

								?>
								<div class="card-body">
									<?php 
									$counter = 0;
									foreach ($topBrands as $row): 
										if ($counter >= 3) break;
									?>
										<div class="card mb-3">
											<div class="card-body d-flex justify-content-between align-items-center">
												<div>
													<h5 class="card-title mb-1"><?php echo $row['brand_name'];?></h5>
												</div>
												<div class="flex-grow-1 text-center">
													<p class="mb-0 fw-bold">Sold: <?php echo $row['total_sold'];?></p>
												</div>
												<a href="brands.php" class="btn btn-primary btn-sm">View</a>
											</div>
										</div>
									<?php 
									$counter++;
									endforeach; 
									?>
								</div>
							</div>
						</div>
						<?php include 'modal_viewallbrand.php'?>
					</div>

					<div class="card">
						<div class="card-header">
							<div class="card-title">Sales Overview</div>
						</div>
						<div class="card-body">
							<div class="chart-container" style="height: 60vh;">
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

					<?php 
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
	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>

	<?php include 'modal_profile.php'?>
	<?php include 'modal_editaccount.php';?>
	<script>
		var labels = <?php echo json_encode($labels); ?>;
		var values = <?php echo json_encode($values); ?>;
		var sales_chart = document.getElementById("sales_chart").getContext("2d");

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
				maintainAspectRatio: false,
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

	<script>
		
		document.getElementById("downloadPdfBtn").addEventListener("click", function () {
				document.getElementById("downloadPdfBtn").style.display = "none";
                const { jsPDF } = window.jspdf;

                const report = document.getElementById("pdf");

                html2canvas(report, { scale: 2 }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jsPDF('p', 'mm', 'a4');

                    const pageWidth = pdf.internal.pageSize.getWidth();
                    const pageHeight = pdf.internal.pageSize.getHeight();
                    const imgWidth = pageWidth;
                    const imgHeight = canvas.height * imgWidth / canvas.width;

                    let heightLeft = imgHeight;
                    let position = 0;

                    pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                    heightLeft -= pageHeight;

                    while (heightLeft > 0) {
                        position = heightLeft - imgHeight;
                        pdf.addPage();
                        pdf.addImage(imgData, 'PNG', 0, position, imgWidth, imgHeight);
                        heightLeft -= pageHeight;
                    }

                    const filename = `report.pdf`;
                    pdf.save(filename);
					document.getElementById("downloadPdfBtn").style.display = "block";
                });
            });
        
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
	<script >
		$(document).ready(function() {
			

			// Add Row
			$('#categories').DataTable({
				"pageLength": 10,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#categories').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
					]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>

    <!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#categories').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getcategorydata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editCategoryName').val(data.category_name);
                        $('#editCategoryId').val(data.category_id);
                        $('#editCategoryModal').modal('show');
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data: " + error);
                    }
                });
            });
        });
    </script>

    <?php if (isset($_GET['status'])): ?>
        <script>
            <?php if ($_GET['status'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Category Added!',
                    text: 'The category has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the category.',
                });
            <?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Category already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Category Edited!',
                    text: 'The category has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the category.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>