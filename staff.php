<?php 
	require ('session.php');
	require ('db.php');

	if($_SESSION['role_id'] == 3){
		header('Location: index.php?access=denied');
		exit();
	}

    $sql = "SELECT u.user_id, u.username, ud.firstname, ud.lastname, ud.email
        FROM users u
        JOIN userdetails ud ON u.user_id = ud.user_id
		WHERE u.branch_id = :branch_id";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $_SESSION['branch_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT * FROM branch";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$branch_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT * FROM roles";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$roles_data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Staff</title>
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

</head>
<body>
	<div class="wrapper">
		<?php $active = 'account';?>
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
								<a href="#">Account Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Staff</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Staff Accounts</h4>
										<?php if($_SESSION['role_id'] != 3):?>
										<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addAccountModal">
											<i class="fa fa-plus"></i>
											Add Account
										</button>
										<?php endif; ?>
									</div>
								</div>
								<div class="card-body">
									<!-- Add Account Modal -->
									<div class="modal fade" id="addAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
										<div class="modal-dialog" role="document">
											<div class="modal-content">
												<div class="modal-header border-0">
													<h5 class="modal-title">
														<span class="fw-mediumbold">
														New</span> 
														<span class="fw-light">
															Account
														</span>
													</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
                                                <form action="process_addaccount.php" method="POST">
												    <div class="modal-body">
													    <p class="small">Create a new account using this form, make sure you fill them all</p>
                                                        <div class="row">
															<div class="col-sm-12 <?php if($_SESSION['role_id'] != 1): echo 'd-none'; endif; ?>">
                                                                <div class="form-group form-group-default">
                                                                    <label>Branch</label>
                                                                    <select name="branch_id" class="form-select" value="<?php if($_SESSION['role_id'] != 1): echo $_SESSION['branch_id']; endif; ?>">
																		<option value="">Select Branch</option>
																		<?php foreach($branch_data as $row):?>
																			<option value="<?php echo $row['branch_id']?>"><?php echo $row['branch_name']?></option>
																		<?php endforeach; ?>
																	</select>
                                                                </div>
                                                            </div>
															<div class="col-sm-12 <?php if($_SESSION['role_id'] != 1): echo 'd-none'; endif; ?>">
                                                                <div class="form-group form-group-default">
                                                                    <label class="<?php if($_SESSION['role_id'] != 1): echo 'd-none'; endif; ?>">Role</label>
                                                                    <select name="role_id" class="form-select <?php if($_SESSION['role_id'] != 1): echo 'd-none'; endif; ?>" value="<?php if($_SESSION['role_id'] != 1): echo '3'; endif; ?>">
																		<option value="">Select Role</option>
																		<?php foreach($roles_data as $row):?>
                                                                            <?php if($row['role_id'] != 3):?>
																			    <option value="<?php echo $row['role_id']?>"><?php echo $row['role_name']?></option>
                                                                            <?php endif; ?>
																		<?php endforeach; ?>
																	</select>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 pe-0">
                                                                <div class="form-group form-group-default">
                                                                    <label>First Name</label>
                                                                    <input type="text" class="form-control" name="firstname" placeholder="fill first name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6">
                                                                <div class="form-group form-group-default">
                                                                    <label>Last Name</label>
                                                                    <input type="text" class="form-control" name="lastname" placeholder="fill last name" required>
                                                                </div>
                                                            </div>
															<div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Email</label>
                                                                    <input type="email" class="form-control" name="email" placeholder="fill email" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Username</label>
                                                                    <input type="text" class="form-control" name="username" placeholder="fill username" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Password</label>
                                                                    <input type="password" class="form-control" name="password" placeholder="fill password" required>
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
										<table id="accounts" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th>Full Name</th>
													<th>Email</th>
													<th>Username</th>
													<?php if($_SESSION['user_id'] == 17): ?>
													<th style="width: 10%">Action</th>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
                                                <?php
                                                    foreach ($data as $row) {
                                                        echo "<tr data-id=".htmlspecialchars($row['user_id']).">";
                                                        echo "<td>". htmlspecialchars($row['firstname']) ." ". htmlspecialchars($row['lastname']) ."</td>";
														echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
														if($_SESSION['user_id'] == 17){
															echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary btn-lg' data-bs-toggle='modal' data-bs-target='#editAccountModal' title='Edit Task'>
                                                                        <i class='fa fa-edit'></i>
                                                                    </button>
                                                                    <button type='button' class='btn btn-link btn-danger remove-btn' data-id='".htmlspecialchars($row['user_id'])."' title='Remove'>
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
                                                        const userId = this.getAttribute('data-id');
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
                                                                xhr.open('POST', 'process_deleteaccount.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The account has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'staff.php';
                                                                            });
                                                                        }else if(xhr.responseText === 'cant'){
                                                                            Swal.fire('Error!', 'You cannot delete your own account.', 'error');
                                                                        } else {
                                                                            Swal.fire('Error!', 'There was an error deleting the account.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('user_id=' + userId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
                                        <?php include 'modal_editaccount.php';?>
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
			$('#accounts').DataTable({
				"pageLength": 10,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#accounts').dataTable().fnAddData([
					$("#addName").val(),
					$("#addPosition").val(),
					$("#addOffice").val(),
					action
					]);
				$('#addRowModal').modal('hide');

			});
		});
	</script>

	<!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#accounts').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getaccountdata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editFirstName').val(data.firstname);
                        $('#editLastName').val(data.lastname);
                        $('#editUsername').val(data.username);
                        $('#editUserId').val(data.user_id);
						$('#editEmail').val(data.email);
						$('#editBranchId').val(data.branch_id);
						$('#editDestination').val('staff.php');
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
                    title: 'Account Added!',
                    text: 'The account has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the account.',
                });
			<?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Username already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

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
</body>
</html>