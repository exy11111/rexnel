<?php 
	require ('session.php');
	require ('db.php');

	$sql = "SELECT * FROM suppliers WHERE branch_id = :branch_id";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Suppliers</title>
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
								<a href="#">Inventory Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Suppliers</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Suppliers</h4>
										<div class="ms-auto">
											<?php if($_SESSION['role_id'] == 1): ?>
											<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
												<i class="fa fa-plus"></i>
												Add Supplier
											</button>
											<?php endif; ?>
											<a href="adminorderhistory.php" class="btn btn-success btn-round ms-auto">
												Order History
											</a>
											
										</div>
									</div>
								</div>
								<div class="card-body">
									<!-- Modal -->
									<div class="modal fade" id="addRowModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header border-0">
													<h5 class="modal-title">
														<span class="fw-mediumbold">
														New</span> 
														<span class="fw-light">
															Supplier
														</span>
													</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
                                                <form action="process_addsupplier.php" method="POST">
                                                    <div class="modal-body">
                                                        <p class="small">Create a new supplier using this form, make sure you fill them all</p>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Supplier Name</label>
                                                                    <input type="text" name="supplier_name" class="form-control" placeholder="fill name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Contact Name</label>
                                                                    <input type="text" name="contact_name" class="form-control" placeholder="fill name" required>
                                                                </div>
                                                            </div>
															<div class="col-sm-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Email</label>
                                                                    <input type="email" name="email" class="form-control" placeholder="fill email" required>
                                                                </div>
                                                            </div>
															<div class="col-sm-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Phone</label>
                                                                    <input type="tel" name="phone" class="form-control" placeholder="fill phone" maxLength="11" oninput="validatePhoneNumber(this)" required>
																	<script>
																		function validatePhoneNumber(input) {
																			input.value = input.value.replace(/[^0-9]/g, '');
																		}
																	</script>
                                                                </div>
                                                            </div>
															<div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Address</label>
                                                                    <textarea type="text" name="address" class="form-control" placeholder="fill address" required></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer border-0">
                                                        <button type="submit" class="btn btn-primary">Add</button>
                                                        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                                                    </div>
                                                </form>
											</div>
										</div>
									</div>

									<div class="table-responsive">
										<table id="sizes" class="display table table-striped table-hover">
											<thead>
												<tr>
													<th>Supplier Name</th>
                                                    <th>Contact Name</th>
                                                    <th>Email</th>
                                                    <th>Phone</th>
                                                    <th>Address</th>
													<?php if($_SESSION['role_id'] == 1): ?>
													<th style="width: 10%">Action</th>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['supplier_id']).">";
														echo "<td>".htmlspecialchars($row['supplier_name'])."</td>";
                                                        echo "<td>".htmlspecialchars($row['contact_name'])."</td>";
                                                        echo "<td>".htmlspecialchars($row['email'])."</td>";
                                                        echo "<td>".htmlspecialchars($row['phone'])."</td>";
                                                        echo "<td>".htmlspecialchars($row['address'])."</td>";
														if($_SESSION['role_id'] == 1){
															echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary btn-lg' data-bs-toggle='modal' data-bs-target='#editSizeModal' title='Edit Task'>
                                                                        <i class='fa fa-edit'></i>
                                                                    </button>
                                                                    <button type='button' class='btn btn-link btn-danger remove-btn' data-id='".htmlspecialchars($row['supplier_id'])."' title='Remove'>
                                                                        <i class='fa fa-times'></i>
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
                                                        const supplierId = this.getAttribute('data-id');
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
                                                                xhr.open('POST', 'process_deletesupplier.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The supplier has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'suppliers.php';
                                                                            });
                                                                        } else if(xhr.responseText === 'exist'){
																			Swal.fire({
																				title: 'Items in this supplier will also be deleted.',
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
																					xhr1.open('POST', 'process_confirmdeletesupplier.php', true);
																					xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
																					xhr1.onload = function() {
																						if (xhr1.status === 200) {
																							if (xhr1.responseText === 'success') {
																								Swal.fire('Deleted!', 'The supplier has been deleted.', 'success').then(() => {
																									window.location.href = 'suppliers.php';
																								});
																							}
																						}
																					}
																					xhr1.send('supplier_id=' + supplierId);
																				}
																			});
																		}else {
                                                                            Swal.fire('Error!', 'There was an error deleting the supplier.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('supplier_id=' + supplierId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
                                        <!-- Edit category modal -->
                                        <div class="modal fade" id="editSizeModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">
                                                            <span class="fw-mediumbold">
                                                            Edit</span> 
                                                            <span class="fw-light">
                                                                Supplier
                                                            </span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="process_editsupplier.php" method="POST">
                                                        <div class="modal-body">
                                                            <p class="small">Edit the supplier details below.</p>
                                                            <div class="row">
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label>Supplier Name</label>
																		<input type="text" name="supplier_name" id="editSupplierName" class="form-control" placeholder="fill name" required>
																	</div>
																</div>
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label>Contact Name</label>
																		<input type="text" name="contact_name" id="editContactName" class="form-control" placeholder="fill name" required>
																	</div>
																</div>
																<div class="col-sm-6">
																	<div class="form-group form-group-default">
																		<label>Email</label>
																		<input type="email" name="email" id="editEmail" class="form-control" placeholder="fill email" required>
																	</div>
																</div>
																<div class="col-sm-6">
																	<div class="form-group form-group-default">
																		<label>Phone</label>
																		<input type="tel" name="phone" id="editPhone" class="form-control" placeholder="fill phone" maxLength="11" oninput="validatePhoneNumber(this)" required>
																		<script>
																			function validatePhoneNumber(input) {
																				input.value = input.value.replace(/[^0-9]/g, '');
																			}
																		</script>
																	</div>
																</div>
																<div class="col-sm-12">
																	<div class="form-group form-group-default">
																		<label>Address</label>
																		<textarea type="text" name="address" id="editAddress" class="form-control" placeholder="fill address" required></textarea>
																	</div>
																</div>
                                                        	</div>
                                                            <input type="hidden" name="supplier_id" id="editSupplierId">
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
			$('#sizes').DataTable({
				"pageLength": 10,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';
		});
	</script>

    <!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#sizes').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getsupplierdata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editSupplierId').val(data.supplier_id);
                        $('#editSupplierName').val(data.supplier_name);
                        $('#editContactName').val(data.contact_name);
						$('#editEmail').val(data.email);
						$('#editPhone').val(data.phone);
						$('#editAddress').val(data.address);
                        $('#editSizeModal').modal('show');
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
                    title: 'Supplier Added!',
                    text: 'The supplier has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the supplier.',
                });
            <?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Supplier already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Supplier Edited!',
                    text: 'The supplier has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the supplier.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>