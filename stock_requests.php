<?php 
	require ('session.php');
	require ('db.php');
    ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

	if(isset($_GET['b'])){
		$_SESSION['branch_id'] = $_GET['b'];
	}
	else if($_SESSION['branch_id'] == 0){
		$_SESSION['branch_id'] = 1;
	}

	$sql = "SELECT sr.id, sr.user_id, sr.item_id, sr.quantity, sr.status, sr.created_at,
    b.branch_name, ud.firstname, ud.lastname,
    i.item_name, c.category_name, s.size_name
    FROM stock_requests sr
    LEFT JOIN branch b ON sr.branch_id = b.branch_id
    LEFT JOIN userdetails ud ON ud.user_id = sr.user_id
    LEFT JOIN items i ON i.item_id = sr.item_id
    LEFT JOIN categories c ON c.category_id = i.category_id
    LEFT JOIN sizes s ON s.size_id = i.size_id
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Stock Requests</title>
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
								<a href="branches.php"><i class="bi bi-arrow-left gear-icon"></i></a>
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
								<a href="#">Stock Requests</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Stock Requests</h4>
										<div class="ms-auto">
										</div>
										
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="add-row" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>Branch</th>
                                                    <th>Requested By</th>
													<th>Date</th>
													<th>Item Name</th>
													<th>Category</th>
                                                    <th>Quantity</th>
													<th>Status</th>
                                                    <th>Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														$status = $row['status'];

                                                        switch ($status) {
                                                            case 'Pending':
                                                                $class = "badge badge-secondary";
                                                                break;

                                                            case 'Accepted':
                                                                $class = "badge badge-info";
                                                                break;

                                                            case 'Shipping':
                                                                $class = "badge badge-primary";
                                                                break;

                                                            case 'Delivered':
                                                                $class = "badge badge-success";
                                                                break;

                                                            case 'Received':
                                                                $class = "badge badge-success";
                                                                break;

                                                            case 'Cancelled':
                                                                $class = "badge badge-danger";
                                                                break;

                                                            default:
                                                                $class = "badge badge-dark";
                                                                break;
                                                        }

														echo "<tr data-id=".htmlspecialchars($row['id']).">";
														echo "<td>".htmlspecialchars($row['branch_name'])."</td>";
														echo "<td>".htmlspecialchars($row['firstname'])." ".htmlspecialchars($row['lastname'])."</td>";
														echo "<td data-order='".strtotime($row['created_at'])."'>" . date("F j, Y g:iA", strtotime($row['created_at'])) . "</td>";
														echo "<td>".htmlspecialchars($row['item_name'])." ".htmlspecialchars($row['size_name'])."</td>";
														echo "<td>".htmlspecialchars($row['category_name'])."</td>";
														echo "<td>".htmlspecialchars($row['quantity'])."</td>";
														echo "<td> <span class='" . $class. "'>".$status."</span></td>";
														if($_SESSION['role_id'] == 1){
															echo "<td>
                                                                    <div class='form-button-action'>";

                                                            // Show EDIT only if status is NOT Received or Cancelled
                                                            if ($status !== 'Received' && $status !== 'Cancelled') {
                                                                echo "<button type='button' 
                                                                    class='btn btn-link btn-primary btn-lg edit-btn'
                                                                    data-bs-toggle='modal' 
                                                                    data-bs-target='#editRequestModal'
                                                                    data-id='".htmlspecialchars($row['id'])."'
                                                                    data-status='".htmlspecialchars($row['status'])."'
                                                                    title='Edit Request'>
                                                                    <i class='fa fa-edit'></i>
                                                                </button>";
                                                            }
                                                            echo "</div>
                                                                </td>";
														}
                                                        echo "</tr>";
													}
												?>
											</tbody>
										</table>
										<!-- Modal -->
										<div class="modal modal-lg fade" id="editRequestModal" tabindex="-1" role="dialog" aria-hidden="true">
											<div class="modal-dialog" role="document">
												<div class="modal-content">
													<div class="modal-header border-0">
														<h5 class="modal-title">
															<span class="fw-mediumbold">
															Edit</span> 
															<span class="fw-light">
																Request
															</span>
														</h5>
														<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
													</div>
													<form action="process_editrequest.php" method="POST">
														<div class="modal-body">
															<p class="small">Edit the request details below.</p>
															<div class="row">
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
                                                                        <label>Status</label>
                                                                        <select name="status" id="editStatus" class="form-control" required>
                                                                            <option value="Pending">Pending</option>
                                                                            <option value="Accepted">Accepted</option>
                                                                            <option value="Shipping">Shipping</option>
                                                                            <option value="Delivered">Delivered</option>
                                                                            <option value="Cancelled">Cancelled</option>
                                                                        </select>
                                                                    </div>
																</div>
															<input type="text" name="id" id="editId" hidden>
														</div>
														<div class="modal-footer border-0">
															<button type="submit" class="btn btn-primary">Save Changes</button>
															<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
														</div>
													</form>
												</div>
											</div>
										</div>
                                        <script>
                                            document.addEventListener("DOMContentLoaded", function() {
                                                const editModal = document.getElementById('editRequestModal');

                                                editModal.addEventListener('show.bs.modal', function (event) {

                                                    // Button that triggered the modal
                                                    let button = event.relatedTarget;

                                                    // Get values from button
                                                    let id = button.getAttribute('data-id');
                                                    let status = button.getAttribute('data-status');

                                                    // Fill modal inputs
                                                    document.getElementById('editId').value = id;
                                                    document.getElementById('editStatus').value = status;
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
	<script >
		$(document).ready(function() {
			

			// Add Row
			$('#add-row').DataTable({
				pageLength: 10,
				order: [[2, 'desc']]
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
                    title: 'Status Edited!',
                    text: 'The status has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the status.',
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