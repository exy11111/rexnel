<?php 
	require ('session.php');
	require ('db.php');

	if(isset($_SESSION['role_id']) && $_SESSION['role_id'] != 1){
		header('Location: index.php');
	}
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

	<!-- Chart JS -->
	<script src="assets/js/plugin/chart.js/chart.min.js"></script>
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
		<?php $active = 'admin';?>
		<?php include ('include_sidebar.php'); ?>

		<div class="main-panel">
			<?php include ('include_navbar.php'); ?>
			
			<div class="container">
				<div class="page-inner">
					<div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
						<div>
							<h3 class="fw-bold mb-3">Dashboard</h3>
						</div>
						<!--
						<div class="ms-md-auto py-2 py-md-0">
							<a href="#" class="btn btn-label-info btn-round me-2">Manage</a>
							<a href="#" class="btn btn-primary btn-round">Add Customer</a>
						</div> 
						-->
					</div>
                    <div class="row mb-3">
						<div class="col-md-2">

						</div>
                        <div class="col-md-8 col-12">
                            <div class="card h-100">
                                <div class="card-header">
                                    <div class="card-title">Sales Overview</div>
                                </div>
                                <div class="card-body">
                                    <div class="chart-container mb-1">
                                        <canvas id="sales_chart"></canvas>
                                    </div>
                                    <div class="row mb-1">
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
						<script>
							fetch('process_getsalesoverview.php')
								.then(response => {
									if (!response.ok) {
										throw new Error('Network response was not ok');
									}
									return response.text();
								})
								.then(text => {
									if (text.trim() === "") {
										throw new Error('Empty response from server');
									}
									try {
										return JSON.parse(text);
									} catch (e) {
										console.error('JSON parse error:', e, text);
										throw e;
									}
								})
								.then(chartData => {
									const ctx = document.getElementById('sales_chart').getContext('2d');
									new Chart(ctx, {
										type: 'line',
										data: chartData,
										options: {
											responsive: true,
											scales: {
												yAxes: [{
													ticks: {
														beginAtZero: true,
														userCallback: function(value) {
															if (typeof value === 'number') {
																return '₱' + value.toLocaleString();
															}
															return value;
														}
													}
												}],
												xAxes: [{
													ticks: {
														autoSkip: true,
														maxTicksLimit: 10,
														userCallback: function(label) {
															var date = new Date(label);
															if (!isNaN(date.getTime())) {
																var options = { year: 'numeric', month: 'short', day: 'numeric' };
																return date.toLocaleDateString('en-US', options);
															}
															return label;
														}
													}
												}]
											}
										}
									});
								})
								.catch(error => {
									console.error('Fetch/Parsing Error:', error);
								});
						</script>
						<div class="col-md-2">
							
						</div>
                    </div>
					
					<div class="row mb-3">
						<?php 
							$sql = "SELECT branch_id, branch_name FROM branch";
							$stmt = $conn->prepare($sql);
							$stmt->execute();
							$branch_data = $stmt->fetchAll();
						?>

						<div class="col-2">

						</div>
						<div class="col-md-8 col-12">
							<div class="card">
								<div class="card-header d-flex justify-content-between align-items-center">
									<div class="card-title">Stock Overview</div>
									<select id="branchSelectChart" class="form-select w-auto">
										<option value="">-- Select Branch --</option>
										<?php foreach($branch_data as $branch): ?>
											<option value="<?php echo htmlspecialchars($branch['branch_id']); ?>">
												<?php echo htmlspecialchars($branch['branch_name']); ?>
											</option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="card-body">
									<div class="chart-container mb-1">
										<canvas id="stockChart"></canvas>
									</div>
								</div>
							</div>
						</div>
						<div class="col-2">
							
						</div>
						<script>
							let stockChart; // global chart reference

							function loadStockChart(branchId) {
								if (!branchId) return;

								fetch(`process_getstockoverview.php?branch_id=${encodeURIComponent(branchId)}`)
									.then(response => {
										if (!response.ok) throw new Error('Network response was not ok');
										return response.json();
									})
									.then(chartData => {
										const ctx = document.getElementById('stockChart').getContext('2d');

										if(stockChart) {
											stockChart.destroy(); // remove previous chart
										}

										stockChart = new Chart(ctx, {
											type: 'bar',
											data: chartData,
											options: {
												responsive: true,
												scales: {
													y: {
														beginAtZero: true,
														ticks: {
															callback: value => value + ' units'
														}
													},
													x: {
														ticks: {
															autoSkip: true,
															maxTicksLimit: 10
														}
													}
												}
											}
										});
									})
									.catch(error => {
										console.error('Fetch/Parsing Error:', error);
									});
							}

							// Load chart when branch is selected
							document.getElementById('branchSelectChart').addEventListener('change', function() {
								const branchId = this.value;
								loadStockChart(branchId);
							});
							</script>
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