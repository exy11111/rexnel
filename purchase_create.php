<?php 
	require ('session.php');
	require ('db.php');

	$sql1 = "SELECT * FROM items WHERE branch_id = :branch_id";
	$stmt1 = $conn->prepare($sql1);
	$stmt1->bindParam('branch_id', $_SESSION['branch_id']);
	$stmt1->execute();
	$data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT * FROM sizes";
	$stmt2 = $conn->prepare($sql2);
	$stmt2->execute();
	$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	$sql3 = "SELECT * FROM payment_method";
	$stmt3 = $conn->prepare($sql3);
	$stmt3->execute();
	$data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

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
						<?php if($_SESSION['role_id'] == 1):?>
						<li class="nav-item">
							<a href="adminpanel.php" class="text-white">
								<i class="fas fa-home"></i>
								<p>Admin Panel</p>
							</a>
						</li>
						<?php endif; ?>
						<li class="nav-item">
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
						<li class="nav-item">
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
									<li>
										<a href="branches.php">
											<span class="sub-item text-white">Branches</span>
										</a>
									</li>
								</ul>
							</div>
						</li>
						<li class="nav-item active">
							<a data-bs-toggle="collapse" href="#sales"  class="text-white">
								<i class="fas fa-layer-group"></i>
								<p>Sales Management</p>
								<span class="caret"></span>
							</a>
							<div class="collapse" id="sales">
								<ul class="nav nav-collapse">
									<li>
										<a href="purchase.php">
											<span class="sub-item text-white">Purchase Log</span>
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
							<?php 
								if(isset($_SESSION['user_id'])){
									$sql = "SELECT username, email, firstname, lastname, created_at, is_verified FROM users JOIN userdetails ON users.user_id = userdetails.user_id WHERE users.user_id = :user_id";
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
										$is_verified = $user_data['is_verified'];
									}
									else {
										$username = "User not found.";
										$email = "User not found.";
									}
								}
							?>
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
													<p class="text-muted"><?php echo $email?></p><a href="profile.html" class="btn btn-xs btn-secondary btn-sm">View Profile</a>
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
						<?php 
						$sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
						$stmt->execute();
						$branch_name = $stmt->fetchColumn();
						?>
						<h3 class="fw-bold mb-3"><?php echo $branch_name; ?></h3>
						<ul class="breadcrumbs mb-3">
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
								<a href="#">Purchase Log</a>
							</li>
                            <li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Add Purchase</a>
							</li>
						</ul>
					</div>
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-12" >
                            <div class="card p-3">
                                <h5 class="mt-2">Add Purchase</h5>
                                <form id="purchaseForm" action="">
                                    <div class="mt-5 mb-3">
										<label for="barcode" class="form-label">Barcode</label>
										<input type="text" class="form-control" id="barcode" name="barcode" required>
                                    </div>
                                    <div class="mb-3">
										<label for="item_name" class="form-label">Item</label>
										<select name="" id="item_id" class="form-select" required>
											<option>Select Item</option>
											<?php foreach($data1 as $row):?>
												<option value="<?php echo $row['item_id']?>"><?php echo $row['item_name']?></option>
											<?php endforeach;?>
										</select>
                                    </div>
                                    <div class="mb-3">
										<label for="price" class="form-label">Price</label>
										<div class="input-group">
											<span class="input-group-text">₱</span>
											<input type="number" class="form-control" step=0.01 id="price" name="price" readonly>
										</div>
									</div>
                                    <div class="row mb-3">
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Category</label>
                                            <input type="text" class="form-control" id="category" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="brand" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Supplier</label>
                                            <input type="text" class="form-control" id="supplier" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Size</label>
                                            <input type="text" class="form-control" id="size" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity" required>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Available Quantity</label>
                                            <input type="text" class="form-control" id="available_stock" readonly>
                                        </div>
                                    </div>
    								<div class="d-flex justify-content-end">
										<button type="submit" class="btn btn-primary mb-2">Submit</button>
									</div>
                                </form>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-3">
								<div class="row">
									<div class="col-6">
										<button class="btn btn-primary mb-2" onclick="submitReceipt()">Checkout</button>
									</div>
									<div class="col-6">
										<h5 class="text-end" id="totalPrice">Total Price: ₱0.00</h5>
									</div>
									
									
								</div>
                                <h5 class="mt-2 text-center fw-bold">House of Local</h5>
								<div class="table-responsive">
                                <table class="table table-bordered" id="receipt">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
							<div class="col-md-4 mt-3">
								<div class="form-group form-group-default">
									<label>Payment Method</label>
									<select class="form-select" name="pm_id" required>
										<option>Choose Payment Method</option>
										<?php foreach ($data3 as $row):?>
											<option value="<?php echo $row['pm_id']?>"><?php echo $row['payment_method']?></option>
										<?php endforeach; ?>
									</select>
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
	<script>
		document.getElementById('barcode').addEventListener('change', function() {
			let barcode = this.value;

			fetch('process_getbarcodedata.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'barcode=' + barcode
			})
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.error
					});
					console.log(data);
					document.getElementById('item_id').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';
				} else {
					console.log(data);
					document.getElementById('item_id').value = data.item_id;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size').value = data.size_name;
					document.getElementById('price').value = data.price;
					document.getElementById('available_stock').value = data.stock;
					document.getElementById('quantity').value = 1;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		document.getElementById('item_id').addEventListener('change', function() {
			let itemId = this.value;

			fetch('process_getbarcodedata.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'item_id=' + itemId
			})
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.error
					});
					document.getElementById('barcode').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';
				} else {
					document.getElementById('barcode').value = data.barcode;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size').value = data.size_name;
					document.getElementById('price').value = data.price;
					document.getElementById('available_stock').value = data.stock;
					document.getElementById('quantity').value = 1;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		function addRow() {
			let item_id = document.getElementById("item_id").value;
			let quantity = parseFloat(document.getElementById("quantity").value);
			let available = parseFloat(document.getElementById('available_stock').value);

			if (!item_id) {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Please enter an item."
				});
				return;
			} else if (quantity < 1) {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Please enter a valid quantity."
				});
				return;
			} else if(quantity > available){
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Item out of stock."
				});
				return;
			}

			fetch("process_getreceiptdata.php?item_id=" + encodeURIComponent(item_id))
				.then(response => response.json())
				.then(data => {
					if (data.error) {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: data.error
						});
						return;
					}

					let table = document.getElementById("receipt").getElementsByTagName("tbody")[0];
					let rows = table.getElementsByTagName("tr");
					let pricePerUnit = parseFloat(data.price);
					let updated = false;

					// Check if item already exists
					for (let i = 0; i < rows.length; i++) {
						let existingItemId = rows[i].getElementsByTagName("td")[5].innerText;
						
						if (existingItemId === item_id) {
							let existingQuantity = parseFloat(rows[i].getElementsByTagName("td")[1].innerText);
							let newQuantity = existingQuantity + quantity;
							let newTotalPrice = newQuantity * pricePerUnit;
							
							rows[i].getElementsByTagName("td")[1].innerText = newQuantity;
							rows[i].getElementsByTagName("td")[3].innerText = "₱" + newTotalPrice.toFixed(2);
							updated = true;
							break;
						}
					}

					if (!updated) {
						let newRow = table.insertRow();
						let cell1 = newRow.insertCell(0);
						let cell2 = newRow.insertCell(1);
						let cell3 = newRow.insertCell(2);
						let cell4 = newRow.insertCell(3);
						let cell5 = newRow.insertCell(4);
						let cell6 = newRow.insertCell(5);

						cell1.innerHTML = data.item_name;
						cell2.innerHTML = quantity;
						cell3.innerHTML = data.size_name;
						cell4.innerHTML = "₱" + (quantity * pricePerUnit).toFixed(2);
						cell5.innerHTML = "<button type='button' class='btn btn-link btn-danger' title='Remove' onclick='deleteRow(this)'><i class='fa fa-times'></i></button>";
						cell6.innerHTML = item_id;
						cell6.style.display = "none";
					}

					document.getElementById("item_id").value = "";
					document.getElementById('barcode').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';

					updateTotalPrice();
				})
				.catch(error => console.error("Error:", error));
		}


		document.getElementById("purchaseForm").addEventListener("submit", function(event) {
			event.preventDefault();
			addRow();
		});

		function updateTotalPrice() {
			let total = 0;
			let table = document.getElementById("receipt");
			let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

			for (let i = 0; i < rows.length; i++) {
				let cell4 = rows[i].getElementsByTagName("td")[3];
				let price = parseFloat(cell4.innerText.replace("₱", "").replace(",", "")) || 0;
				total += price;
			}

			document.getElementById("totalPrice").innerText = "Total Price: ₱" + total.toFixed(2);
		}

		function deleteRow(button) {
			let row = button.parentNode.parentNode;
			row.parentNode.removeChild(row);
			updateTotalPrice();
		}

		function submitReceipt() {
			let table = document.getElementById("receipt");
			let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
			let receiptData = [];

			let paymentMethod = document.querySelector("select[name='pm_id']").value;
			if (paymentMethod === "Choose Payment Method") {
				Swal.fire("Error!", "Please select a valid payment method.", "error");
				return;
			}

			let totalPriceText = document.getElementById("totalPrice").innerText;
			let totalPrice = parseFloat(totalPriceText.replace("Total Price: ₱", "").replace(",", ""));

			for (let i = 0; i < rows.length; i++) {
				let cells = rows[i].getElementsByTagName("td");

				let rowData = {
					quantity: parseInt(cells[1].innerText), // Convert to integer
					price: parseFloat(cells[3].innerText.replace("₱", "").trim()).toFixed(2), // Convert to decimal (10,2)
					item_id: parseInt(cells[5] ? cells[5].innerText : "0") // Convert to integer (default 0 if empty)
				};

				receiptData.push(rowData);
			}


			if (receiptData.length === 0) {
				Swal.fire("Error!", "Your receipt is empty. Add items before checking out.", "error");
				return;
			}

			Swal.fire({
				title: "Are you sure you want to checkout?",
				text: "You won't be able to undo this action.",
				icon: "warning",
				showCancelButton: true,
				confirmButtonText: "Yes, Checkout!",
				cancelButtonText: "Cancel"
			}).then((result) => {
				if (result.isConfirmed) {

					if (paymentMethod === "2") { // adjust "1" to your actual GCash pm_id
						Swal.fire({
							title: "Scan to Pay via GCash",
							text: "Please scan this QR code before confirming payment.",
							imageUrl: "gcash.jpg",
							imageAlt: "GCash QR Code",
							showCancelButton: true,
							confirmButtonText: "Paid",
							cancelButtonText: "Cancel"
						}).then((qrResult) => {
							if (qrResult.isConfirmed) {
								fetch("process_receipt.php", {
									method: "POST",
									headers: { "Content-Type": "application/json" },
									body: JSON.stringify({
										receipt: receiptData,
										total_price: totalPrice,
										payment_method: paymentMethod,
										branch_id: <?php echo $_SESSION['branch_id'];?>
									})
								})
								.then(response => response.json())
								.then(data => {
									if (data.success) {
										Swal.fire({
											title: "Success!",
											text: "Purchase submitted successfully!",
											icon: "success",
											confirmButtonText: "OK"
										}).then(() => {
											window.location.href = "purchase.php";
										});
									} else {
										Swal.fire("Error!", data.error, "error");
									}
								})
								.catch(error => console.error("Error:", error));
							}
						});
					}
					else{
						fetch("process_receipt.php", {
							method: "POST",
							headers: { "Content-Type": "application/json" },
							body: JSON.stringify({
								receipt: receiptData,
								total_price: totalPrice,
								payment_method: paymentMethod,
								branch_id: <?php echo $_SESSION['branch_id'];?>
							})
						})
						.then(response => response.json())
						.then(data => {
							if (data.success) {
								Swal.fire({
									title: "Success!",
									text: "Purchase submitted successfully!",
									icon: "success",
									confirmButtonText: "OK"
								}).then(() => {
									window.location.href = "purchase.php";
								});
							} else {
								Swal.fire("Error!", data.error, "error");
							}
						})
						.catch(error => console.error("Error:", error));
					}

					
				}
			});
		}

	</script>
</body>
</html>