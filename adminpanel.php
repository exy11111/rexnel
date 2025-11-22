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
								<?php if ($_SESSION['role_id'] == 1):?>
									<style>
									.gear-icon {
										cursor: pointer;
										transition: color 0.2s, background-color 0.2s;
										padding: 3px;
										border-radius: 4px;
									}

									.gear-icon:hover {
										background-color:rgb(192, 192, 192);
										color: black;
									}
									</style>

									<?php 
										$sql = "SELECT * from branch";
										$stmt = $conn->prepare($sql);
										$stmt->execute();
										$branches = $stmt->fetchAll();
									?>
									<i class="bi bi-gear-fill gear-icon me-2" 
									data-bs-toggle="modal" 
									data-bs-target="#editBranchModal"></i>
								<?php endif; ?>
								<?php echo $branch_name; ?>
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
						$sql = "SELECT SUM(price) FROM purchases WHERE branch_id = :branch_id";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
						$stmt->execute();
						$all_revenue = $stmt->fetchColumn();

						$sql = "SELECT SUM(amount) FROM supplier_orders so JOIN items i ON so.item_id = i.item_id WHERE i.branch_id = :branch_id";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
						$stmt->execute();
						$all_expenses = $stmt->fetchColumn();

						$all_profit = $all_revenue - $all_expenses;
					?>
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
													<p class="card-category">Total Revenue</p>
													<h4 class="card-title">₱<?php echo number_format($all_revenue ?? 0.00, 2) ?></h4>
													
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
													<p class="card-category">Total Profit</p>
													<h4 class="card-title">₱<?php echo number_format($all_profit, 2) ?></h4>
													
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>	
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
										<div class="card-title">Stock Overview</div>
									</div>
									<div class="card-body">
										<div class="chart-container">
											<canvas id="items_chart2"></canvas>
										</div>
										<div class="dropdown mb-3">
											<button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
												Filter Items
											</button>
											<ul class="dropdown-menu overflow-auto" id="itemFilterList2" style="max-height: 200px;">
												<?php foreach ($itemNames as $index => $item): ?>
													<li>
														<label class="dropdown-item">
															<input type="checkbox" class="form-check-input me-1 item-filter2" value="<?= $index ?>" checked>
															<?= htmlspecialchars($item) ?>
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
			window.location.href = 'index.php?b=' + encodeURIComponent(branchId);
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

		$sql = "SELECT item_name, stock, size_name FROM items JOIN sizes ON items.size_id = sizes.size_id WHERE is_disabled = 0";
		$stmt = $conn->prepare($sql);
		$stmt->execute();
		$items2 = $stmt->fetchAll(PDO::FETCH_ASSOC);
		
		$itemNames2 = [];
		$itemStocks2 = [];
		$colors2 = [];
		
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
			$itemNames2[] = $item['item_name'].' '.$item['size_name'];
			$itemStocks2[] = $item['stock'];
			
			if ($item['stock'] < $lowStockThreshold) {
				$colors2[] = 'rgb(255, 99, 71)'; 
			} else {
				$colors2[] = 'rgb(34, 193, 34)';
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

		var itemNames2 = <?php echo json_encode($itemNames2); ?>;
		var itemStocks2 = <?php echo json_encode($itemStocks2); ?>;
		var colors2 = <?php echo json_encode($colors2); ?>;

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
			type: 'bar',
			data: {
				labels: itemNames2,
				datasets: [{
					label: "Stock",
					backgroundColor: colors2,
					borderColor: colors2,
					data: itemStocks2
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

				myItemsChart2.data.labels = filteredLabels;
				myItemsChart2.data.datasets[0].data = filteredStocks;
				myItemsChart2.data.datasets[0].backgroundColor = filteredColors;
				myItemsChart2.data.datasets[0].borderColor = filteredColors;

				myItemsChart2.update();
			});
		});

		document.querySelectorAll(".item-filter2").forEach(function (checkbox) {
			checkbox.addEventListener("change", function () {
				let selectedIndices = [];
				document.querySelectorAll(".item-filter2:checked").forEach(cb => {
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
											if(!isNaN(date.getTime())) {
												return date.toLocaleDateString('en-US', { year:'numeric', month:'short', day:'numeric' });
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