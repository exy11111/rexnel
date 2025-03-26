<?php 
	require ('session.php');
	require ('db.php');

	$sql = "SELECT transaction_id, date, amount, payment_method FROM transactions t JOIN payment_method p ON t.pm_id = p.pm_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql1 = "SELECT * FROM items";
	$stmt1 = $conn->prepare($sql1);
	$stmt1->execute();
	$data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT * FROM sizes";
	$stmt2 = $conn->prepare($sql2);
	$stmt2->execute();
	$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

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
										<a href="items.php">
											<span class="sub-item">Items</span>
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
									<li>
										<a href="transactions.php">
											<span class="sub-item">Transactions</span>
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
                        <div class="col-6">
                            <div class="card p-3">
                                <h5 class="mt-2">Add Purchase</h5>
                                <form action="" id="purchaseForm">
                                    <div class="mt-5 mb-3">
										<label for="barcode" class="form-label">Barcode</label>
										<input type="text" class="form-control" id="barcode" name="barcode" required>
                                    </div>
                                    <div class="mb-3">
										<label for="item_name" class="form-label">Item</label>
										<select name="" id="item_id" class="form-select">
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
                                            <select name="size" id="size_id" class="form-select" disabled>
												<option>Select Size</option>
												<?php foreach($data2 as $row):?>
													<option value="<?php echo $row['size_id']?>"><?php echo $row['size_name']?></option>
												<?php endforeach;?>
											</select>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Available Quantity</label>
                                            <input type="text" class="form-control" id="available_stock" readonly>
                                        </div>
                                    </div>
    								<div class="d-flex justify-content-end">
										<button class="btn btn-primary mb-2">Submit</button>
									</div>
                                </form>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-3">
								<div class="row">
									<div class="col-6">
										<button class="btn btn-primary mb-2">Checkout</button>
									</div>
									<div class="col-6">
										<h5 class="text-end">Total Price: ₱0.00</h5>
									</div>
									
									
								</div>
                                <h5 class="mt-2 text-center fw-bold">House of Local</h5>
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
                                        <tr>
                                            <td>T-Shirt White</td>
                                            <td>1</td>
                                            <td>S</td>
                                            <td>₱100.00</td>
                                            <td>
                                                <div class='form-button-action'>
                                                    <button type='button' class='btn btn-link btn-danger remove-btn' title='Remove'>
                                                        <i class="bi bi-trash-fill fs-3"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
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
					document.getElementById('item_id').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size_id').disabled = true;
				} else {
					document.getElementById('item_id').value = data.item_id;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size_id').disabled = false;
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
					document.getElementById('size_id').disabled = true;
				} else {
					document.getElementById('barcode').value = data.barcode;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size_id').disabled = false;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		document.getElementById('size_id').addEventListener('change', function() {
			let sizeId = this.value;
			let itemId = document.getElementById('item_id').value;

			let url = `process_getbarcodedata.php?size_id=${encodeURIComponent(sizeId)}&item_id=${encodeURIComponent(itemId)}`;

			fetch(url, {
				method: 'GET',
				headers: {
					'Content-Type': 'application/x-www-form-urlencoded'
				}
			})
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.error
					});
					document.getElementById('price').value = '';
				} else {
					document.getElementById('price').value = data.price;
					document.getElementById('available_stock').value = data.quantity;
					document.getElementById('quantity').value = 1;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		function addRow() {
			let table = document.getElementById("receipt").getElementsByTagName('tbody')[0];
			let newRow = table.insertRow();

			let cell1 = newRow.insertCell(0);
			let cell2 = newRow.insertCell(1);
			let cell3 = newRow.insertCell(2);
			let cell4 = newRow.insertCell(3);
			let cell5 = newRow.insertCell(4);

			cell1.innerHTML = "";
			cell2.innerHTML = "30";
			cell3.innerHTML = '<button onclick="deleteRow(this)">Delete</button>';
		}
	</script>
</body>
</html>