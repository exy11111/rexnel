<?php 
	require ('session.php');
	require ('db.php');
	if($_SESSION['role_id'] != 3){
		header("Location: index.php");
        exit();
	}
	else{
		$supplier_id = $_SESSION['supplier_id'];
		$sql = "SELECT * FROM suppliers WHERE supplier_id = :id";
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id', $supplier_id);
		$stmt->execute();
		$supplier_info = $stmt->fetch();
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

	$sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
	$stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
	$stmt->execute();
	$branch_name = $stmt->fetchColumn();

	$sql = "SELECT purchase_id, price, date, payment_method FROM purchases p1 JOIN payment_method p2 ON p1.pm_id = p2.pm_id ORDER BY p1.date DESC";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title><?php echo $supplier_info['supplier_name'];?></title>
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
		<?php $active = 'dashboard'?>
		<?php include 'include_sidebar_supplier.php'?>

		<div class="main-panel">
		<?php include 'include_navbar_supplier.php'?>
			
			<div class="container">
				<div class="page-inner">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
						<div>
							<h3 class="fw-bold mb-3"><?php echo $supplier_info['supplier_name'];?></h3>
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
													<p class="card-category">Total Orders</p>
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
													<p class="card-category">Pending Orders</p>
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
													<p class="card-category">Revenue this month</p>
													<h4 class="card-title"><?php echo $result1['orders']?></h4>
												</div>
											</div>
										</div>
									</div>
								</div>
							</a>
						</div>
			<div class="row">
				<div class="col-md-12">
					<div class="card">
						<div class="card-header">
							<div class="d-flex align-items-center">
								<h4 class="card-title">Pending</h4>
								<?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
								<?php endif; ?>
							</div>
						</div>						
						<div class="table-responsive">
										<table id="categories" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th style="width: 10%">Order ID</th>
													<th>Date</th>
													<?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2):?>
													<th style="width: 10%">Status</th>
													<th style="width: 10%">Total</th>
													<th style="width: 10%">Action</th>
													<?php endif;?>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['purchase_id']).">";
														echo "<td>".htmlspecialchars($row['date'])."</td>";
														echo "<td>₱".htmlspecialchars($row['price'])."</td>";
														echo "<td>".htmlspecialchars($row['payment_method'])."</td>";
														echo "<td>".htmlspecialchars($row['payment_method'])."</td>";
														echo "<td>
                                                                <div class='form-button-action'>
                                                                    <a href='purchase_view.php?purchase_id=".$row['purchase_id']."' class='btn btn-link btn-primary btn-lg' data-id='".htmlspecialchars($row['purchase_id'])."' title='Edit Task'>
                                                                        <i class='bi bi-eye-fill'></i>
                                                                    </a>
                                                                </div>
                                                            </td>";
                                                        echo "</tr>";
														if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2){

														}
														
                                                        echo "</tr>";

													}
												?>
											</tbody>
										</table>
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