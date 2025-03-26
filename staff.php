<?php 
	require ('session.php');
	require ('db.php');

    $sql = "SELECT u.user_id, u.username, ud.firstname, ud.lastname 
        FROM users u
        JOIN userdetails ud ON u.user_id = ud.user_id";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
                        <li class="nav-item active">
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
						<li class="nav-item">
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
						<h3 class="fw-bold mb-3">Staff</h3>
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
										<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addAccountModal">
											<i class="fa fa-plus"></i>
											Add Account
										</button>
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
													<th>Username</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
                                                <?php
                                                    foreach ($data as $row) {
                                                        echo "<tr data-id=".htmlspecialchars($row['user_id']).">";
                                                        echo "<td>". htmlspecialchars($row['firstname']) ." ". htmlspecialchars($row['lastname']) ."</td>";
                                                        echo "<td>" . htmlspecialchars($row['username']) . "</td>";
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
                                        <!-- Edit Account Modal -->
                                        <div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">
                                                            <span class="fw-mediumbold">Edit</span> 
                                                            <span class="fw-light">Account</span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="process_editaccount.php" method="POST">
                                                        <div class="modal-body">
                                                            <p class="small">Edit the account details below.</p>
                                                            <div class="row">
                                                                <div class="col-md-6 pe-0">
                                                                    <div class="form-group form-group-default">
                                                                        <label>First Name</label>
                                                                        <input type="text" class="form-control" name="firstname" id="editFirstName" placeholder="Enter first name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Last Name</label>
                                                                        <input type="text" class="form-control" name="lastname" id="editLastName" placeholder="Enter last name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Username</label>
                                                                        <input type="text" class="form-control" name="username" id="editUsername" placeholder="Enter username" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Password</label>
                                                                    <input type="password" class="form-control" name="password" placeholder="fill password">
                                                                </div>
                                                            </div>
                                                                <input type="hidden" name="user_id" id="editUserId">
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer border-0">
                                                            <button type="submit" class="btn btn-primary">Save changes</button>    
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