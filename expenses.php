<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
	require ('session.php');
	require ('db.php');

	if($_SESSION['role_id'] == 3){
		header("Location: index.php");
        exit();
	}

    if(isset($_GET['b'])){
		$_SESSION['branch_id'] = $_GET['b'];
	}
	else if($_SESSION['branch_id'] == 0){
		$_SESSION['branch_id'] = 1;
	}


	$sql = "SELECT e.expense_id, et.expense_name, e.comment, e.amount, e.created_at, b.branch_name
    FROM expenses e
    LEFT JOIN expensetype et ON e.expensetype_id = et.expensetype_id
    LEFT JOIN branch b ON b.branch_id = e.branch_id
    WHERE e.branch_id = :branch_id
    ORDER BY e.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = "SELECT * FROM expensetype";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $expensetype = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Expenses</title>
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
								<a href="#">Expense Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="suppliers.php">Expenses</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
                                    <div class="d-flex align-items-center">
										<h4 class="card-title">Expenses</h4>
										<div class="ms-auto">
											<?php if($_SESSION['role_id'] == 1):?>
											<button class="btn btn-success btn-round" data-bs-toggle="modal" data-bs-target="#addExpenseModal">
												<i class="fa fa-plus"></i>
												Add Expense
											</button>
											<?php endif; ?>
										</div>
									</div>
								</div>
								<div class="card-body">
									<div class="table-responsive">
										<table id="sizes" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>ID</th>
													<th>Date</th>
													<th>Type</th>
                                                    <th>Amount</th>
                                                    <th>Comment</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['expense_id']).">";
                                                        echo "<td>".htmlspecialchars($row['expense_id'])."</td>";
														echo "<td>" . date("F d, Y", strtotime($row['created_at'])) . "</td>";
														echo "<td>".htmlspecialchars($row['expense_name'])."</td>";
                                                        echo "<td>₱".number_format($row['amount'], 2)."</td>";
														echo "<td>".htmlspecialchars($row['comment'])."</td>";
														echo "<td>
																<div class='form-button-action'>
																	<a href='#'
                                                                        class='btn btn-link btn-primary btn-lg edit-expense-btn'
                                                                        data-bs-toggle='modal'
                                                                        data-bs-target='#editExpenseModal'
                                                                        data-id='".htmlspecialchars($row["expense_id"])."'
                                                                        data-type='".htmlspecialchars($row["expensetype_id"])."'
                                                                        data-amount='".htmlspecialchars($row["amount"])."'
                                                                        data-comment='".htmlspecialchars($row["comment"])."'
                                                                        title='Edit Expense'>
                                                                        <i class='bi bi-pencil-square'></i>
                                                                    </a>
                                                                    <a href='#' class='btn btn-link btn-danger btn-lg remove-btn' 
																		data-id='".htmlspecialchars($row['expense_id'])."' 
																		title='Remove Expense'>
																		<i class='bi bi-trash'></i>
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
                                                                xhr.open('POST', 'process_deleteexpense.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The expense has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'expenses.php';
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
																					xhr1.open('POST', 'process_deleteexpense.php', true);
																					xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
																					xhr1.onload = function() {
																						if (xhr1.status === 200) {
																							if (xhr1.responseText === 'success') {
																								Swal.fire('Deleted!', 'The expense has been deleted.', 'success').then(() => {
																									window.location.href = 'expenses.php';
																								});
																							}
																						}
																					}
																					xhr1.send('size_id=' + sizeId);
																				}
																			});
																		}else {
                                                                            Swal.fire('Error!', 'There was an error deleting the expense.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('expense_id=' + sizeId);
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
	<div class="modal fade" id="addExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title">
						<span class="fw-mediumbold">
						Add</span> 
						<span class="fw-light">
							Expense
						</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="process_addexpense.php" method="POST">
					<div class="modal-body">
						<p class="small">Add an expense using this form.</p>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label>Expense Type</label>
									<select class="form-select" name="expensetype_id" required>
										<option value="">Select Type</option>
										<?php 
											foreach ($expensetype as $row){
												echo "<option value='".$row['expensetype_id']."'>".$row['expense_name']."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label for="category">Amount (₱)</label>
									<input type="number" class="form-control" name="amount" placeholder="1500" required>
								</div>
							</div>
                            <div class="col-sm-12">
								<div class="form-group form-group-default">
									<label for="category">Comment</label>
									<input type="text" class="form-control" name="comment" required>
								</div>
							</div>

						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="submit" class="btn btn-success">Submit</button>
						<button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
					</div>
				</form>
			</div>
		</div>
	</div>
    <div class="modal fade" id="editExpenseModal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header border-0">
					<h5 class="modal-title">
						<span class="fw-mediumbold">
						Edit</span> 
						<span class="fw-light">
							Expense
						</span>
					</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<form action="process_editexpense.php" method="POST">
                    <input type="hidden" name="expense_id" id="editExpenseId">
					<div class="modal-body">
						<p class="small">Edit an expense using this form.</p>
						<div class="row">
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label>Expense Type</label>
									<select class="form-select" name="expensetype_id" id="editExpenseTypeId" required>
										<option value="">Select Type</option>
										<?php 
											foreach ($expensetype as $row){
												echo "<option value='".$row['expensetype_id']."'>".$row['expense_name']."</option>";
											}
										?>
									</select>
								</div>
							</div>
							<div class="col-sm-12">
								<div class="form-group form-group-default">
									<label for="category">Amount (₱)</label>
									<input type="number" class="form-control" name="amount" id="editAmount" placeholder="1500" required>
								</div>
							</div>
                            <div class="col-sm-12">
								<div class="form-group form-group-default">
									<label for="category">Comment</label>
									<input type="text" class="form-control" name="comment" id="editComment" required>
								</div>
							</div>

						</div>
					</div>
					<div class="modal-footer border-0">
						<button type="submit" class="btn btn-success">Submit</button>
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
                    title: 'Expense Added!',
                    text: 'The expense has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the expense.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>


	<?php if (isset($_GET['order'])): ?>
        <script>
            <?php if ($_GET['order'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Order placed successfully!',
                    text: 'The order has been successfully placed.',
                }).then((result) => {
                });
            <?php elseif ($_GET['order'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while placing the order.',
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
	<script>
	document.addEventListener('DOMContentLoaded', function() {
	const checkButtons = document.querySelectorAll('.check-btn');

	checkButtons.forEach(button => {
		button.addEventListener('click', function(event) {
		event.preventDefault();
		const orderId = this.getAttribute('data-id');

		Swal.fire({
			title: 'Mark order as received?',
			text: "Are you sure you want to mark order #" + orderId + " as received?",
			icon: 'question',
			showCancelButton: true,
			confirmButtonText: 'Yes, mark as received!',
			cancelButtonText: 'Cancel',
			confirmButtonColor: '#28a745',
			cancelButtonColor: '#d33',
		}).then((result) => {
			if (result.isConfirmed) {
			fetch('mark_received.php', {  
				method: 'POST',
				headers: {
				'Content-Type': 'application/json',
				},
				body: JSON.stringify({ order_id: orderId })
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					Swal.fire(
					'Marked!',
					'Order #' + orderId + ' has been marked as received.',
					'success'
				).then(() => {
					// Refresh the page after the user clicks "OK"
					location.reload();
				});
				} else {
				Swal.fire(
					'Error!',
					data.message || 'Failed to update order status.',
					'error'
				);
				}
			})
			.catch(error => {
				Swal.fire(
				'Error!',
				'Something went wrong.',
				'error'
				);
				console.error('Error:', error);
			});
			}
		});
		});
	});
	});
	</script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".edit-expense-btn").forEach(btn => {
                btn.addEventListener("click", function () {
                    
                    document.getElementById("editExpenseTypeId").value = this.dataset.type;
                    document.getElementById("editAmount").value = this.dataset.amount;
                    document.getElementById("editComment").value = this.dataset.comment;

                    // optional hidden input to know what ID to update
                    let hiddenId = document.getElementById("editExpenseId");
                    if (hiddenId) hiddenId.value = this.dataset.id;
                });
            });
        });
    </script>
</body>
</html>