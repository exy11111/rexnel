<?php 
	require ('session.php');
	require ('db.php');

	if(isset($_GET['b'])){
		$_SESSION['branch_id'] = $_GET['b'];
	}
	else if($_SESSION['branch_id'] == 0){
		$_SESSION['branch_id'] = 1;
	}

	$sql = "SELECT item_id, barcode, item_name, category_name, brand_name, supplier_name, size_name, price, stock, supplier_price
	FROM items i 
	JOIN categories c ON i.category_id = c.category_id
	JOIN brands b ON b.brand_id = i.brand_id
	JOIN suppliers s ON s.supplier_id = i.supplier_id
	JOIN sizes ss ON i.size_id = ss.size_id
	WHERE i.branch_id = :branch_id AND is_disabled = 1";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql1 = "SELECT category_id, category_name FROM categories";
	$stmt1 = $conn->prepare($sql1);
    $stmt1->execute();
    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT * FROM brands";
	$stmt2 = $conn->prepare($sql2);
    $stmt2->execute();
    $data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	$sql3 = "SELECT * FROM suppliers";
	$stmt3 = $conn->prepare($sql3);
    $stmt3->execute();
    $data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

	$sql4 = "SELECT * FROM sizes";
	$stmt4 = $conn->prepare($sql4);
    $stmt4->execute();
    $data4 = $stmt4->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Archive</title>
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
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
	<div class="wrapper">
		<?php $active = 'inventory';?>
		<?php include ('include_sidebar.php'); ?>

		<div class="main-panel">
			<?php include ('include_navbar.php'); ?>
			
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
								<a href="#">Inventory Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Archive</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Archive</h4>
										<div class="ms-auto">

										</div>
										
									</div>
								</div>
								<div class="card-body">

									<div class="table-responsive">
										<table id="add-row" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th style="width: 15%">Barcode</th>
													<th>Item Name</th>
													<th>Category</th>
													<th>Brand</th>
													<th>Supplier</th>
													<th>Size</th>
													<th>Price</th>
													<th>Stock</th>
													<th>Status</th>
													<?php if($_SESSION['role_id'] == 1):?> <th style="width: 10%">Action</th> <?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														if($row['stock'] < 1){
															$status = "No Stock";
															$class = "badge badge-danger";
														}
														else if($row['stock'] <= 5){
															$status = "Low Stock";
															$class = "badge badge-warning";
														}
														else{
															$status = "In Stock";
															$class = "badge badge-success";
														}

														echo "<tr data-id=".htmlspecialchars($row['item_id']).">";
														echo "<td>".htmlspecialchars($row['barcode'])."</td>";
														echo "<td>".htmlspecialchars($row['item_name'])."</td>";
														echo "<td>".htmlspecialchars($row['category_name'])."</td>";
														echo "<td>".htmlspecialchars($row['brand_name'])."</td>";
														echo "<td>".htmlspecialchars($row['supplier_name'])."</td>";
														echo "<td>".htmlspecialchars($row['size_name'])."</td>";
														echo "<td>₱" . number_format($row['price'], 2) . "</td>";
														echo "<td>" . number_format($row['stock']) . "</td>";
														echo "<td> <span class='" . $class. "'>".$status."</span></td>";
														if($_SESSION['role_id'] == 1){
															echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary remove-btn' data-id='".htmlspecialchars($row['item_id'])."' title='Remove'>
                                                                        <i class='bi bi-arrow-counterclockwise'></i>
                                                                    </button>
                                                                </div>
                                                            </td>";
														}
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
                                                        const itemId = this.getAttribute('data-id');
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
                                                                xhr.open('POST', 'process_deleteitem.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The item has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'stock.php';
                                                                            });
                                                                        } else if(xhr.responseText === 'exist'){
																			Swal.fire({
																				title: 'Stock in this item will also be deleted.',
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
																					xhr1.open('POST', 'process_confirmdeleteitem.php', true);
																					xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
																					xhr1.onload = function() {
																						if (xhr1.status === 200) {
																							if (xhr1.responseText === 'success') {
																								Swal.fire('Deleted!', 'The item has been deleted.', 'success').then(() => {
																									window.location.href = 'stock.php';
																								});
																							}
																						}
																					}
																					xhr1.send('brand_id=' + brandId);
																				}
																			});
																		}
																		else {
                                                                            Swal.fire('Error!', 'There was an error deleting the item.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('item_id=' + itemId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
										<!-- Modal -->
										<div class="modal modal-lg fade" id="editItemModal" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header border-0">
														<h5 class="modal-title">
															<span class="fw-mediumbold">
															Edit</span> 
															<span class="fw-light">
																Item
															</span>
														</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<form action="process_edititem.php" method="POST">
														<div class="modal-body">
															<p class="small">Edit the item details below.</p>
															<div class="row">
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label>Barcode</label>
																		<input type="text" name="barcode" id="editBarcode" class="form-control" oninput="validatePhoneNumber(this)" placeholder="fill barcode" required>
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
																		<input type="text" name="item_name" id="editItemName" class="form-control" placeholder="fill name" required>
																	</div>
																</div>
																
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label for="category">Category</label>
																		<select class="form-select" id="editCategoryId" name="category_id" required>
																			<option value="">Select Category</option>
																			<?php 
																				foreach ($data1 as $row){
																					echo "<option value='".$row['category_id']."'>".$row['category_name']."</option>";
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label for="category">Brand</label>
																		<select class="form-select" id="editBrandId" name="brand_id" required>
																		<option value="">Select Brand</option>
																			<?php 
																				foreach ($data2 as $row){
																					echo "<option value='".$row['brand_id']."'>".$row['brand_name']."</option>";
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label for="category">Supplier</label>
																		<select class="form-select" id="editSupplierId" name="supplier_id" required>
																			<option value="">Select Supplier</option>
																			<?php 
																				foreach ($data3 as $row){
																					echo "<option value='".$row['supplier_id']."'>".$row['supplier_name']."</option>";
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-4">
																	<div class="form-group form-group-default">
																		<label for="category">Size</label>
																		<select class="form-select" name="size_id" id="editSizeId" required>
																			<option value="">Select Size</option>
																			<?php 
																				foreach ($data4 as $row){
																					echo "<option value='".$row['size_id']."'>".$row['size_name']."</option>";
																				}
																			?>
																		</select>
																	</div>
																</div>
																<div class="col-sm-4">
																	<div class="form-group form-group-default">
																		<label>Price</label>
																		<input type="number" name="price" step=0.01 id="editPrice" class="form-control" placeholder="fill price" required>
																	</div>
																</div>
																<div class="col-sm-4">
																	<div class="form-group form-group-default">
																		<label>Stock</label>
																		<input type="number" name="stock" id="editStock" class="form-control" placeholder="fill stock" required>
																	</div>
																</div>
															</div>
															<input type="text" name="item_id" id="editItemId" hidden>
														</div>
														<div class="modal-footer border-0">
															<button type="submit" class="btn btn-primary">Save Changes</button>
															<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
														</div>
													</form>
												</div>
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
			window.location.href = 'stock.php?b=' + encodeURIComponent(branchId);
		} else {
			Swal.fire({
				icon: 'warning',
				title: 'Oops...',
				text: 'Please select a branch!'
			});
		}
	});
	</script>

	<script>
		function updateUnitCost() {
		const select = document.getElementById('order_itemId');
		const selectedOption = select.options[select.selectedIndex];
		const unitCost = selectedOption.getAttribute('data-unit-cost') || 0;

		const unitCostDisplay = document.getElementById('unit_cost_display');
		unitCostDisplay.textContent = '₱' + parseFloat(unitCost).toFixed(2);

		calculateTotal();
		}

		function calculateTotal() {
		const quantity = parseFloat(document.getElementById('quantity').value) || 0;
		const unitCostText = document.getElementById('unit_cost_display').textContent.replace('₱', '');
		const unitCost = parseFloat(unitCostText) || 0;

		const total = quantity * unitCost;
		document.getElementById('total_price').textContent = '₱' + total.toFixed(2);
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
	<script >
		$(document).ready(function() {
			

			// Add Row
			$('#add-row').DataTable({
				"pageLength": 10,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#add-row').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
					]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>

	<?php if (isset($_GET['status'])): ?>
        <script>
            <?php if ($_GET['status'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Item Added!',
                    text: 'The item has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the item.',
                });
            <?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Item already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

	<?php if (isset($_GET['stockstatus'])): ?>
        <script>
            <?php if ($_GET['stockstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Item Added!',
                    text: 'The item has been successfully updated.',
                }).then((result) => {
                });
            <?php elseif ($_GET['stockstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while updating the item.',
                });
            <?php elseif ($_GET['stockstatus'] == 'less'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Please enter a valid quantity.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

	<?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Item Edited!',
                    text: 'The item has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the item.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
	<?php if (isset($_GET['order'])): ?>
        <script>
            <?php if ($_GET['order'] == 'success'): ?>
				Swal.fire({
					icon: 'success',
					title: 'Order placed.',
					text: 'You have successfully placed an order.',
					showCancelButton: true,
					confirmButtonText: 'View in Orders',
					cancelButtonText: 'OK',
				}).then((result) => {
					if (result.isConfirmed) {
						window.location.href = 'adminorderhistory.php'; 
					}
				});
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the item.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

	<!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#add-row').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getitemdata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
						$('#editBarcode').val(data.barcode);
						$('#editItemId').val(data.item_id);
						$('#editItemName').val(data.item_name);
                        $('#editCategoryId').val(data.category_id);
                        $('#editBrandId').val(data.brand_id);
                        $('#editSupplierId').val(data.supplier_id);
						$('#editSizeId').val(data.size_id);
						$('#editPrice').val(data.price);
						$('#editStock').val(data.stock);
						
                    },
                    error: function(xhr, status, error) {
                        console.error("Error fetching data: " + error);
                    }
                });
            });
        });

		document.getElementById('stock_barcode').addEventListener('change', function() {
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
					document.getElementById('stock_itemId').value = '';
					document.getElementById('stock_quantity').value = '';
				} else {
					console.log(data);
					document.getElementById('stock_itemId').value = data.item_id;
					document.getElementById('stock_quantity').value = 1;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		document.getElementById('stock_barcode2').addEventListener('change', function() {
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
					document.getElementById('order_itemId').value = '';
					document.getElementById('order_quantity').value = '';
				} else {
					console.log(data);
					document.getElementById('order_itemId').value = data.item_id;
					document.getElementById('order_quantity').value = 1;
				}
			})
			.catch(error => console.error('Error:', error));
		});
    </script>



</body>
</html>