<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require ('session.php');
	require ('db.php');

	if($_SESSION['role_id'] == 2){
		header('Location: index.php');
		exit;
	}

	if(isset($_GET['b'])){
		$_SESSION['branch_id'] = $_GET['b'];
	}
	else if($_SESSION['branch_id'] == 0){
		$_SESSION['branch_id'] = 1;
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

	$dateNow = date("Y-m-d"); // today's date in Y-m-d format

	$sql1 = "SELECT SUM(price) AS total_sales, COUNT(purchase_id) AS orders 
			FROM purchases 
			WHERE DATE(date) = :date AND branch_id = :branch_id";
	$stmt1 = $conn->prepare($sql1);
	$stmt1->bindParam(":branch_id", $_SESSION['branch_id']);
	$stmt1->bindParam(":date", $dateNow);
	$stmt1->execute();

	$result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
	$result1['total_sales'] = $result1['total_sales'] ?? 0;
	$result1['orders']      = $result1['orders'] ?? 0;

	$sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
	$stmt->execute();
	$branch_name = $stmt->fetchColumn();

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
		}.nav-item.active a {
			color: #fff !important;
		}.chart-legend-scroll {
			max-height: 350px;
			overflow-y: auto;
			padding-right: 10px;
		}

		.chart-legend-scroll ul {
			list-style: none;
			padding-left: 0;
			margin: 0;
		}

		.chart-legend-scroll li {
			display: flex;
			align-items: center;
			margin-bottom: 8px;
			font-size: 13px;
			cursor: pointer;
			white-space: nowrap;
		}

		.chart-legend-scroll li:hover {
			opacity: 0.7;
		}

		.chart-legend-scroll .color-box {
			width: 14px;
			height: 14px;
			margin-right: 8px;
			flex-shrink: 0;
		}.chart-center-wrapper {
			display: flex;
			justify-content: center;   
			align-items: center;       
			min-height: 350px;        
		}
	</style>

</head>
<body>
	<div class="wrapper">
		<?php $active = 'index';?>
		<?php include ('include_sidebar.php'); ?>
		

		<div class="main-panel">
			<?php include ('include_navbar.php'); ?>
			
			<div class="container">
				<div class="page-inner">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
						<div>
							<h3 class="fw-bold mb-3">
								Dashboard
							</h3>
						</div>
						<!--
						<div class="ms-md-auto py-2 py-md-0">
							<a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
							<a href="#" class="btn btn-primary btn-round">Add Customer</a>
						</div> 
						-->
					</div>

					<!-- -->
					<?php 
						$sql = "SELECT SUM(price) FROM purchases";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$all_revenue = $stmt->fetchColumn();

						$sql = "SELECT SUM(amount) FROM supplier_orders so JOIN items i ON so.item_id = i.item_id";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$supplier_expenses = $stmt->fetchColumn();

						$sql = "SELECT SUM(amount) FROM expenses";
						$stmt = $conn->prepare($sql);
						$stmt->execute();
						$expenses = $stmt->fetchColumn();

						$all_expenses = $supplier_expenses + $expenses;


						$all_profit = $all_revenue - $all_expenses;

						if ($all_expenses > 0) {
							$roi = ($all_profit / $all_expenses) * 100;
						} else {
							$roi = 0;
						}
					?>
					<div class="row">
						<div class="col-sm-6 col-md-3">
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
													<p class="card-category">Total Revenue</p>
													<h4 class="card-title">₱<?php echo number_format($all_revenue ?? 0.00, 2) ?></h4>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
						<div class="col-sm-6 col-md-3">
							<a href="expenses.php">
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
													<p class="card-category">Total Expenses</p>
													<h4 class="card-title">₱<?php echo number_format($all_expenses ?? 0.00, 2)  ?></h4>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
						<div class="col-sm-6 col-md-3">
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
													<p class="card-category">Total Profit</p>
													<h4 class="card-title">₱<?php echo number_format($all_profit, 2) ?></h4>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
						<div class="col-sm-6 col-md-3">
							<a href="report_select.php">
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
													<p class="card-category">Return of Investment</p>
													<h4 class="card-title"><?= number_format($roi, 2) ?>%</h4>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
					</div>

					<div class="row">
						
						
						<!-- FETCH -->
						<?php
							$sql = "SELECT item_name, stock, size_name FROM items JOIN sizes ON items.size_id = sizes.size_id WHERE items.branch_id = :branch_id AND is_disabled = 0";
							$stmt = $conn->prepare($sql);
							$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
							$stmt->execute();
							$items = $stmt->fetchAll(PDO::FETCH_ASSOC);
							
							$itemNames = [];
							$itemStocks = [];
							$colors = [];

							$sql = "SELECT items.item_name, items.stock, sizes.size_name, branch.branch_name, categories.category_name 
							FROM items
							JOIN sizes ON items.size_id = sizes.size_id
							JOIN branch ON items.branch_id = branch.branch_id
							JOIN categories ON items.category_id = categories.category_id
							WHERE is_disabled = 0";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$items2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
							$itemData2 = [];

							$sql = "SELECT * FROM branch";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$br = $stmt->fetchAll();
							
							
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

							foreach ($items2 as $item) {
								$itemData2[] = [
									'label' => $item['branch_name'].' '.$item['item_name'].' '.$item['size_name'],
									'stock' => $item['stock'],
									'color' => ($item['stock'] < $lowStockThreshold) 
										? 'rgb(255, 99, 71)'                   // red for low stock
										: generateColorFromString($item['item_name']),
									'category' => $item['category_name'],
									'branch' => $item['branch_name']
								];
							}

							function generateColorFromString($str) {
								// hash the string to get a number
								$hash = crc32($str);
								
								// Use bit masking to get RGB values
								$r = ($hash & 0xFF0000) >> 16;
								$g = ($hash & 0x00FF00) >> 8;
								$b = $hash & 0x0000FF;

								// Avoid mostly-red colors (reserved for low stock)
								if ($r > 200 && $g < 80 && $b < 80) {
									$r = 100 + ($r % 100);
									$g = 100 + ($g % 100);
									$b = 100 + ($b % 100);
								}

								return "rgb($r, $g, $b)";
							}

							$categories = [];
							$branches = [];
							foreach ($items2 as $item) {
								$categories[$item['category_name']] = true;
								$branches[$item['branch_name']] = true;
							}
							$categories = array_keys($categories);

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
							<div class="col-2">

							</div>
							<div class="col-md-8 d-flex flex-column">
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
							<div class="col-2">

							</div>
						</div>

						<div class="row">

							<div class="col-md-6 d-flex flex-column">
								<div class="card h-100">
									<div class="card-header">
										<div class="card-title">Stock Overview (<?php echo $branch_name ?>)</div>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="items_chart"></canvas>
										</div>
										<div class="dropdown mb-3">
											<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
												Filter Items
											</button>
											<ul class="dropdown-menu overflow-auto" id="itemFilterList" style="max-height: 200px;">
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
							</div>

							<div class="col-md-6 d-flex flex-column">
								<div class="card">
									<div class="card-header">
										<div class="card-title">Stock Overview (All Branches)</div>
									</div>
									<div class="card-body">
										<div class="row align-items-start">
											<!-- LEGEND (LEFT) -->
											<div class="col-md-4">
												<div id="itemsLegend2" class="chart-legend-scroll"></div>
											</div>

											<!-- PIE CHART (RIGHT) -->
											<div class="col-md-8 d-flex">
												<div class="chart-center-wrapper w-100">
													<canvas id="items_chart2"></canvas>
												</div>
											</div>
										</div>
										<div class="row mt-3">
											<div class="col-6">
												<div class="dropdown mb-3">
													<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
														Filter Categories
													</button>
													<ul class="dropdown-menu overflow-auto" id="itemFilterList2" style="max-height: 200px;">
														<?php foreach ($categories as $category): ?>
															<li>
																<label class="dropdown-item">
																	<input type="checkbox" class="form-check-input me-1 item-filter2" value="<?= htmlspecialchars($category) ?>" checked>
																	<?= htmlspecialchars($category) ?>
																</label>
															</li>
														<?php endforeach; ?>
													</ul>
												</div>
											</div>
											<div class="col-6 text-end">
												<div class="dropdown mb-3">
													<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
														Filter Branches
													</button>
													<ul class="dropdown-menu overflow-auto" id="branchFilterList2" style="max-height: 200px;">
														<?php foreach ($br as $branch): ?>
															<li>
																<label class="dropdown-item">
																	<input type="checkbox" class="form-check-input me-1 branch-filter2" value="<?= htmlspecialchars($branch['branch_name']) ?>" checked>
																	<?= htmlspecialchars($branch['branch_name']) ?>
																</label>
															</li>
														<?php endforeach; ?>
													</ul>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="row">
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
												case 'week':
													$filterLabel = "This Week";  // new case added
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

											$selectedBranchId = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';
											$selectedBranchName = 'All Branches';

											if ($selectedBranchId !== 'all') {
												foreach ($br as $branch) {
													if ($branch['branch_id'] == $selectedBranchId) {
														$selectedBranchName = $branch['branch_name'];
														break;
													}
												}
											}
											
										?>
										<div class="d-flex align-items-center">
											<div class="dropdown ms-2">
												<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="branchFilterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
													<?= htmlspecialchars($selectedBranchName) ?>
												</button>
												<ul class="dropdown-menu" aria-labelledby="branchFilterDropdown">
													<!-- All Branches option -->
													<?php
														$queryParams = $_GET;
														$queryParams['branch_id'] = 'all';
														$allUrl = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($queryParams);
													?>
													<li><a class="dropdown-item" href="<?= $allUrl ?>">All Branches</a></li>

													<!-- Individual branch options -->
													<?php foreach ($br as $branch): ?>
														<?php
															$queryParams = $_GET;
															$queryParams['branch_id'] = $branch['branch_id'];
															$url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($queryParams);
														?>
														<li>
															<a class="dropdown-item" href="<?= $url ?>"><?= htmlspecialchars($branch['branch_name']) ?></a>
														</li>
													<?php endforeach; ?>
												</ul>
											</div>
											<div class="dropdown ms-2">
												<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdown" data-bs-toggle="dropdown" aria-expanded="false">
													<?= htmlspecialchars($filterLabel) ?>
												</button>
												<?php
													$queryParams = $_GET;

													$productFilters = [
														'today' => 'Today',
														'week'  => 'This Week',  // new filter
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
										$selectedBranch = isset($_GET['branch_id']) ? $_GET['branch_id'] : 'all';

										$where = "WHERE 1";
										$params = [];
										
										$today = date('Y-m-d');
										$startOfWeek = date('Y-m-d', strtotime('monday this week'));
										$endOfWeek = date('Y-m-d', strtotime('sunday this week'));
										$thisMonth = date('m');
										$thisYear  = date('Y');

										if ($topProductFilter === 'today') {
											$where .= " AND DATE(p.date) = :today";
											$params[':today'] = $today;
										} elseif ($topProductFilter === 'week') {
											$where .= " AND DATE(p.date) BETWEEN :startOfWeek AND :endOfWeek";
											$params[':startOfWeek'] = $startOfWeek;
											$params[':endOfWeek'] = $endOfWeek;
										}
										 elseif ($topProductFilter === 'month') {
											$where .= " AND YEAR(p.date) = :year AND MONTH(p.date) = :month";
											$params[':year']  = $thisYear;
											$params[':month'] = $thisMonth;
										} elseif ($topProductFilter === 'year') {
											$where .= " AND YEAR(p.date) = :year";
											$params[':year'] = $thisYear;
										}
										if ($selectedBranch !== 'all') {
											$where .= " AND p.branch_id = :branch_id";
											$params[':branch_id'] = $selectedBranch;
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
													<div>
														<div class="flex-grow-1 text-center">
															<p class="mb-0 fw-bold">Sold: <?php echo $row['total_sold'];?></p>
														</div>
														<a href="stock.php" class="btn btn-primary btn-sm">View</a>
													</div>
													
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
											$selectedBranchBrand = isset($_GET['top_brand_branch']) ? $_GET['top_brand_branch'] : 'all';

											switch ($selectedFilterBrand) {
												case 'today':
													$filterLabelBrand = "Today";
													break;
												case 'week':                       // new case
													$filterLabelBrand = "This Week";
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
											<div class="dropdown ms-2">
												<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="branchFilterDropdownBrand" data-bs-toggle="dropdown" aria-expanded="false">
													<?php 
														$branchLabel = 'All Branches';
														if ($selectedBranchBrand !== 'all') {
															foreach ($br as $branch) {
																if ($branch['branch_id'] == $selectedBranchBrand) {
																	$branchLabel = $branch['branch_name'];
																	break;
																}
															}
														}
														echo htmlspecialchars($branchLabel);
													?>
												</button>
												<ul class="dropdown-menu" aria-labelledby="branchFilterDropdownBrand">
													<?php
													// “All Branches” option
													$params = $_GET;
													$params['top_brand_branch'] = 'all';
													$url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($params);
													?>
													<li><a class="dropdown-item" href="<?= $url ?>">All Branches</a></li>

													<?php foreach ($br as $branch): 
														$params = $_GET;
														$params['top_brand_branch'] = $branch['branch_id'];
														$url = basename($_SERVER['PHP_SELF']) . '?' . http_build_query($params);
													?>
														<li><a class="dropdown-item" href="<?= $url ?>"><?= htmlspecialchars($branch['branch_name']) ?></a></li>
													<?php endforeach; ?>
												</ul>
											</div>
											<div class="dropdown ms-2">
												<button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" id="filterDropdownBrand" data-bs-toggle="dropdown" aria-expanded="false">
													<?= htmlspecialchars($filterLabelBrand) ?>
												</button>
												<?php
												$brandFilters = [
													'all'   => 'All Time',
													'today' => 'Today',
													'week'  => 'This Week',   // new option
													'month' => 'This Month',
													'year'  => 'This Year'
												];
												?>
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

										$whereBrand = "WHERE 1";
										$paramsBrand = [];
										
										$today = date('Y-m-d');
										$startOfWeek = date('Y-m-d', strtotime('monday this week'));
										$endOfWeek   = date('Y-m-d', strtotime('sunday this week'));
										$thisMonth = date('m');
										$thisYear  = date('Y');
										
										if ($topBrandFilter === 'today') {
											$whereBrand .= " AND DATE(p.date) = :today";
											$paramsBrand[':today'] = $today;
										} elseif ($topBrandFilter === 'week') {              // new condition
											$whereBrand .= " AND DATE(p.date) BETWEEN :startOfWeek AND :endOfWeek";
											$paramsBrand[':startOfWeek'] = $startOfWeek;
											$paramsBrand[':endOfWeek'] = $endOfWeek;
										} 
										elseif ($topBrandFilter === 'month') {
											$whereBrand .= " AND YEAR(p.date) = :year AND MONTH(p.date) = :month";
											$paramsBrand[':year']  = $thisYear;
											$paramsBrand[':month'] = $thisMonth;
										} elseif ($topBrandFilter === 'year') {
											$whereBrand .= " AND YEAR(p.date) = :year";
											$paramsBrand[':year'] = $thisYear;
										}	
										if ($selectedBranchBrand !== 'all') {
											$whereBrand .= " AND p.branch_id = :branch_id";
											$paramsBrand[':branch_id'] = $selectedBranchBrand;
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
													<div>
														<div class="flex-grow-1 text-center">
															<p class="mb-0 fw-bold">Sold: <?php echo $row['total_sold'];?></p>
														</div>
														<a href="brands.php" class="btn btn-primary btn-sm">View</a>
													</div>
													
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
                    text: 'The account has been successfully edited. If you edited your email, please check your inbox.',
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
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
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

	<script>
		const rawData2 = <?= json_encode($itemData2) ?>;

		const pieLabels2 = rawData2.map(i => i.label);
		const pieValues2 = rawData2.map(i => i.stock);
		const pieColors2 = rawData2.map(i => i.color);

		const ctx2 = document.getElementById('items_chart2').getContext('2d');

		const itemsChart2 = new Chart(ctx2, {
			type: 'pie',
			data: {
				labels: pieLabels2,
				datasets: [{
					data: pieValues2,
					backgroundColor: pieColors2
				}]
			},
			options: {
				responsive: true,
				plugins: {
					legend: {
						display: false // 👈 disable built-in legend
					},
					tooltip: {
						callbacks: {
							label: (ctx) => `${ctx.label}: ${ctx.raw} stock`
						}
					}
				}
			}
		});


		// ✅ Create scrollable legend
		function generateScrollableLegend(chart, containerId) {
			const container = document.getElementById(containerId);
			container.innerHTML = '<ul></ul>';

			chart.data.labels.forEach((label, i) => {
				const li = document.createElement('li');

				li.innerHTML = `
					<span class="color-box" style="background:${chart.data.datasets[0].backgroundColor[i]}"></span>
					${label} (${chart.data.datasets[0].data[i]})
				`;

				// click = toggle slice
				li.onclick = () => {
					chart.toggleDataVisibility(i);
					chart.update();
				};

				container.querySelector('ul').appendChild(li);
			});
		}

		generateScrollableLegend(itemsChart2, 'itemsLegend2');
		</script>


	<!-- Kaiadmin JS -->
	<script src="assets/js/kaiadmin.min.js"></script>

	<?php include 'modal_profile.php'?>
	<?php include 'modal_editaccount.php';?>

	<!-- Edit Branch Modal -->
	<div class="modal fade" id="editBranchModal" tabindex="-1" aria-hidden="true">
		<div class="modal-dialog modal-sm modal-dialog-centered">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title">Select Branch</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<select id="branchSelect" class="form-select">
						<option value="">-- Select Branch --</option>
						<?php foreach($branches as $branch): ?>
							<option value="<?php echo htmlspecialchars($branch['branch_id']); ?>">
								<?php echo htmlspecialchars($branch['branch_name']); ?>
							</option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="modal-footer border-0">
					<button type="button" id="confirmBranchBtn" class="btn btn-primary">Confirm</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
	<script>
	document.getElementById('confirmBranchBtn').addEventListener('click', function() {
		const select = document.getElementById('branchSelect');
		const branchId = select.value;

		if(branchId) {
			window.location.href = 'adminpanel.php?b=' + encodeURIComponent(branchId);
		} else {
			Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Please select a branch!'
			});
		}
	});
	</script>

	<?php
		$sql = "SELECT item_name, stock, size_name FROM items JOIN sizes ON items.size_id = sizes.size_id WHERE items.branch_id = :branch_id AND is_disabled = 0";
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

		var itemData2 = <?php echo json_encode($itemData2); ?>;

		var labels = <?php echo json_encode($labels); ?>;
    	var values = <?php echo json_encode($values); ?>;
		
		var items_chart = document.getElementById("items_chart").getContext("2d");
		var items_chart2 = document.getElementById("items_chart2").getContext("2d");
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

		var myItemsChart2 = new Chart(items_chart2, {
			type: 'pie',
			data: {
				labels: itemData2.map(item => item.label),
				datasets: [{
					label: "Stock",
					backgroundColor:  itemData2.map(item => item.color),
					borderColor:  itemData2.map(item => item.color),
					data: itemData2.map(item => item.stock)
				}],
			},
			options: {
				responsive: true,
				maintainAspectRatio: false,
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

		document.querySelectorAll(".item-filter2").forEach(function (checkbox) {
			checkbox.addEventListener("change", function () {
				const selectedCategories = Array.from(document.querySelectorAll(".item-filter2:checked"))
												.map(cb => cb.value);

				const filteredData = itemData2.filter(item => selectedCategories.includes(item.category));

				myItemsChart2.data.labels = filteredData.map(d => d.label);
				myItemsChart2.data.datasets[0].data = filteredData.map(d => d.stock);
				myItemsChart2.data.datasets[0].backgroundColor = filteredData.map(d => d.color);
				myItemsChart2.data.datasets[0].borderColor = filteredData.map(d => d.color);

				myItemsChart2.update();
			});
		});

		document.querySelectorAll(".branch-filter2").forEach(function (checkbox) {
			checkbox.addEventListener("change", function () {
				const selectedBranch = Array.from(document.querySelectorAll(".branch-filter2:checked"))
												.map(cb => cb.value);

				const filteredData = itemData2.filter(item => selectedBranch.includes(item.branch));

				myItemsChart2.data.labels = filteredData.map(d => d.label);
				myItemsChart2.data.datasets[0].data = filteredData.map(d => d.stock);
				myItemsChart2.data.datasets[0].backgroundColor = filteredData.map(d => d.color);
				myItemsChart2.data.datasets[0].borderColor = filteredData.map(d => d.color);

				myItemsChart2.update();
			});
		});

		const startDateInput = document.getElementById('startDate');
		const endDateInput = document.getElementById('endDate');

		[startDateInput, endDateInput].forEach(input => {
			input.addEventListener('change', () => {
				const startDate = startDateInput.value;
				const endDate = endDateInput.value;

				// Only fetch if both dates are selected (optional)
				if(startDate && endDate) {
					loadSalesChart(startDate, endDate);
				}
			});
		});

		let salesChart; // to destroy previous chart

		function loadSalesChart(startDate = '', endDate = '') {
			// Build query string
			let url = 'process_getsalesoverview.php';
			if(startDate && endDate) {
				url += `?start=${encodeURIComponent(startDate)}&end=${encodeURIComponent(endDate)}`;
			}

			const colors = [
			'#36A2EB', // blue
			'#4CAF50', // green
			'#FFCE56', // yellow
			'#9C27B0', // purple
			'#FF9800', // orange
			'#795548', // brown
			'#607D8B', // blue-gray
			'#00BCD4', // cyan
			'#3F51B5', // indigo
			'#8BC34A'  // light green
			];

			fetch(url)
				.then(response => {
					if(!response.ok) throw new Error('Network response was not ok');
					return response.text();
				})
				.then(text => {
					if(text.trim() === "") throw new Error('Empty response from server');
					return JSON.parse(text);
				})
				.then(chartData => {
					const ctx = document.getElementById('sales_chart').getContext('2d');

					chartData.datasets.forEach((ds, index) => {
						const color = colors[index % colors.length];
						ds.borderColor = color;
						ds.backgroundColor = color + '33';
						ds.pointBackgroundColor = color;
					});
					// Destroy previous chart if exists
					if(salesChart) salesChart.destroy();

					salesChart = new Chart(ctx, {
						type: 'line',
						data: chartData,
						options: {
							responsive: true,
							scales: {
								y: {
									beginAtZero: true,
									ticks: {
										callback: value => '₱' + value.toLocaleString()
									}
								},
								x: {
									ticks: {
										autoSkip: true,
										maxTicksLimit: 10,
										callback: label => {
											const date = new Date(label);
											if (!isNaN(date.getTime())) {
												return date.toLocaleDateString('en-US', {
													year: 'numeric',
													month: 'short',
													day: 'numeric'
												});
											}
											return label;
										}
									}
								}
							}
						}
					});
				})
				.catch(error => console.error('Fetch/Parsing Error:', error));
		}
		loadSalesChart();

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
		if (isNaN(percentageChange)) {
			percentageChange = '0';
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
    <script src="editmodal.js"></script>
</body>
</html>