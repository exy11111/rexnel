<?php 
	require ('session.php');
	require ('db.php');

	if($_SESSION['role_id'] == 3){
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

	$sql = "SELECT so.order_id, b.branch_name, so.date, so.amount, so.status 
	FROM supplier_orders so 
	JOIN items i ON so.item_id = i.item_id 
	JOIN branch b ON i.branch_id = b.branch_id
	WHERE i.branch_id = :branch_id
	ORDER BY so.date DESC";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Order History</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/holicon.png" type="image/x-icon"/><link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

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
</head>
<body>
	<div class="wrapper">
		<!-- Sidebar -->
		<?php $active = 'expenses'?>
		<?php include 'include_sidebar.php'?>

		<div class="main-panel">
		<?php include 'include_navbar.php'?>
			
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
								<a href="#">Account Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="suppliers.php">Suppliers</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Order History</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
                                    <div class="d-flex align-items-center">
										<h4 class="card-title">Orders</h4>
										<div class="ms-auto">
											<button class="btn btn-success btn-round" data-bs-toggle="modal" data-bs-target="#orderModal">
												<i class="fa fa-plus"></i>
												Add Order
											</button>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="sizes" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>Order ID</th>
													<th>Date</th>
													<th>Total Amount</th>
                                                    <th>Status</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['order_id']).">";
														echo "<td>".htmlspecialchars($row['order_id'])."</td>";
														echo "<td>" . date("F d, Y", strtotime($row['date'])) . "</td>";
														echo "<td>₱".htmlspecialchars($row['amount'])."</td>";
                                                        $status = htmlspecialchars($row['status']);
														$badgeClass = '';
														
														switch ($status) {
															case 'Pending':
																$badgeClass = 'bg-warning text-dark';
																break;
															case 'Accepted':
																$badgeClass = 'bg-primary';
																break;
															case 'Shipping':
																$badgeClass = 'bg-info text-dark';
																break;
															case 'Delivered':
																$badgeClass = 'bg-success';
																break;
															case 'Received':
																$badgeClass = 'bg-secondary';
																break;
															case 'Cancelled':
																$badgeClass = 'bg-danger';
																break;
															default:
																$badgeClass = 'bg-light text-dark';
														}
														echo "<td><span class='badge $badgeClass'>$status</span></td>";
														echo "<td>
                                                                <div class='form-button-action'>
                                                                    <a href='#' class='btn btn-link btn-primary btn-lg' 
																		data-bs-toggle='modal' 
																		data-bs-target='#viewItemModal' 
																		data-id='".htmlspecialchars($row['order_id'])."' 
																		title='View Order'>
																		<i class='bi bi-eye-fill'></i>
																	</a>
                                                                </div>
                                                            </td>";
                                                        echo "</tr>";
													}
												?>
											</tbody>
										</table>
                                        
                                        <script>
                                            document.addEventListener('DOMContentLoaded', function() {
                                                const removeButtons = document.querySelectorAll('.remove-btn');
                                                
                                                removeButtons.forEach(button => {
                                                    button.addEventListener('click', function() {
                                                        const sizeId = this.getAttribute('data-id');
                                                        Swal.fire({
                                                            title: 'Are you sure?',
                                                            text: "This action cannot be undone!",
                                                            icon: 'warning',
                                                            showCancelButton: true,
                                                            confirmButtonColor: '#d33',
                                                            cancelButtonColor: '#3085d6',
                                                            confirmButtonText: 'Yes, delete it!',
                                                            cancelButtonText: 'Cancel'
                                                        }).then((result) => {
                                                            if (result.isConfirmed) {
                                                                const xhr = new XMLHttpRequest();
                                                                xhr.open('POST', 'process_deletesize.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The size has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'sizes.php';
                                                                            });
                                                                        } else if(xhr.responseText === 'exist'){
																			Swal.fire({
																				title: 'Stocks in this size will also be deleted.',
																				text: "Are you sure? This action cannot be undone!",
																				icon: 'warning',
																				showCancelButton: true,
																				confirmButtonColor: '#d33',
																				cancelButtonColor: '#3085d6',
																				confirmButtonText: 'Yes, delete it!',
																				cancelButtonText: 'Cancel'
																			}).then((result) => {
																				if (result.isConfirmed) {
																					const xhr1 = new XMLHttpRequest();
																					xhr1.open('POST', 'process_confirmdeletesize.php', true);
																					xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
																					xhr1.onload = function() {
																						if (xhr1.status === 200) {
																							if (xhr1.responseText === 'success') {
																								Swal.fire('Deleted!', 'The size has been deleted.', 'success').then(() => {
																									window.location.href = 'sizes.php';
																								});
																							}
																						}
																					}
																					xhr1.send('size_id=' + sizeId);
																				}
																			});
																		}else {
                                                                            Swal.fire('Error!', 'There was an error deleting the size.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('size_id=' + sizeId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
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
	<?php 
	
	$sql = "SELECT item_id, barcode, item_name, category_name, brand_name, supplier_name, size_name, price, stock, supplier_price
	FROM items i 
	JOIN categories c ON i.category_id = c.category_id
	JOIN brands b ON b.brand_id = i.brand_id
	JOIN suppliers s ON s.supplier_id = i.supplier_id
	JOIN sizes ss ON i.size_id = ss.size_id
	WHERE i.branch_id = :branch_id";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $itemdata = $stmt->fetchAll(PDO::FETCH_ASSOC);
	
	?>
	<div class="modal fade" id="orderModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title">
						<span class="fw-mediumbold">
						Add</span> 
						<span class="fw-light">
							Order
						</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="process_addsupplierorder.php" method="POST">
					<div class="modal-body">
						<p class="small">Add an order using this form.</p>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label>Barcode</label>
									<input type="text" name="barcode" id="stock_barcode2" class="form-control" oninput="validatePhoneNumber(this)" placeholder="fill barcode">
									<script>
										function validatePhoneNumber(input) {
											input.value = input.value.replace(/[^0-9]/g, '');
										}
									</script>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label>Item Name</label>
									<select class="form-select" name="item_id" id="order_itemId" required>
										<option value="">Select Item</option>
										<?php 
											foreach ($itemdata as $row){
												echo "<option value='".$row['item_id']."' data-price='".$row['supplier_price']."'>".$row['item_name']."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label for="category">Quantity</label>
									<input type="number" class="form-control" name="quantity" id="order_quantity" required>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="d-flex justify-content-between align-items-center" style="padding: 8px 0;">
									<span>Unit Price</span>
									<span id="unit_cost_display" style="color: #333;">₱0.00</span>
								</div>
							</div>

							<div class="col-sm-12">
								<div class="d-flex justify-content-between align-items-center" style="padding: 8px 0; font-weight: 700; font-size: 1.25rem; color: #E94E1B;">
									<span>Total Price</span>
									<span id="total_price">₱0.00</span>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="submit" class="btn btn-success">Order</button>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
				<script>
					const itemSelect = document.getElementById("order_itemId");
					const unitCostDisplay = document.getElementById("unit_cost_display");
					const totalPriceDisplay = document.getElementById("total_price");
					const quantityInput = document.getElementById("order_quantity");

					function updatePrices() {
						const selectedOption = itemSelect.options[itemSelect.selectedIndex];
						const unitPrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
						const quantity = parseInt(quantityInput.value) || 0;
						const total = unitPrice * quantity;

						unitCostDisplay.textContent = "₱" + unitPrice.toLocaleString(undefined, {minimumFractionDigits: 2});
						totalPriceDisplay.textContent = "₱" + total.toLocaleString(undefined, {minimumFractionDigits: 2});
					}

					itemSelect.addEventListener("change", updatePrices);
					quantityInput.addEventListener("input", updatePrices);
				</script>
			</div>
		</div>
	</div>
	<!-- Modal -->
	<div class="modal modal-lg fade" id="editStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header border-0">
				<h5 class="modal-title">
					<span class="fw-mediumbold">
					Edit</span> 
					<span class="fw-light">
						Status
					</span>
				</h5>
				<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
			</div>
			<form action="process_editstatus_supplier.php" method="POST">
				<div class="modal-body">
					<p class="small">Edit the Status below.</p>
					<div class="row">		
						<div class="col-sm-12">
							<div class="form-group form-group-default">
								<label for="category">Status</label>
								<select class="form-select" id="editStatus" name="status" required>
									<option value="">Select Status</option>
									<option value="Pending">Pending</option>
									<option value="Accepted">Accepted</option>
									<option value="Shipping">Shipping</option>
									<option value="Delivered">Delivered</option>
									<option value="Received">Received</option>
								</select>
							</div>
						</div>
					</div>
					<input type="text" name="item_id" id="editOrderId" hidden>
				</div>
				<div class="modal-footer border-0">
					<button type="submit" class="btn btn-primary">Save Changes</button>
					<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
				</div>
			</form>
		</div>
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

    <?php if (isset($_GET['status'])): ?>
        <script>
            <?php if ($_GET['status'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Size Added!',
                    text: 'The size has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the size.',
                });
            <?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Size already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Size Edited!',
                    text: 'The size has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the size.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>