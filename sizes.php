<?php 
	require ('session.php');
	require ('db.php');
	$branch_id = $_SESSION['branch_id'];

	$sql = "SELECT * FROM sizes WHERE branch_id = :branch_id";
    $stmt = $conn->prepare($sql);
	$stmt->bindParam(':branch_id', $branch_id);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Sizes</title>
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
						<li class="nav-item active">
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
						<li class="nav-item">
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
									} else {
										$username = "User not found.";
										$email = "User not found.";
									}
								}

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
													<p class="text-muted"><?php echo $email?></p><a href="#" class="btn btn-xs btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#profileModal">View Profile</a>
												</div>
											</div>
										</li>
										<li>
											<?php if($_SESSION['user_id'] == 17):?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="adminportal.php">Change Branch</a>
											<?php endif; ?>
											<div class="dropdown-divider"></div>
											<a class="dropdown-item" href="#" id="accountSetting" data-bs-toggle="modal" data-bs-target="#editAccountModal" data-id="<?php if(isset($_SESSION['user_id'])){echo $_SESSION['user_id'];}  ?>">Account Setting</a>
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
								<a href="#">Inventory Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Sizes</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Sizes</h4>
										<?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?>
										<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Add Size
										</button>
										<?php endif; ?>
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
															Size
														</span>
													</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
                                                <form action="process_addsize.php" method="POST">
                                                    <div class="modal-body">
                                                        <p class="small">Create a new size using this form, make sure you fill them all</p>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Size Name</label>
                                                                    <input type="text" name="size_name" class="form-control" placeholder="fill name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Size Description</label>
                                                                    <input type="text" name="size_description" class="form-control" placeholder="fill description" required>
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
										<table id="sizes" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th style="width: 10%; display: none;">ID</th>
													<th style="width: 20%">Size Name</th>
													<th>Size Description</th>
													<?php if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2): ?><th style="width: 10%">Action</th><?php endif; ?>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['size_id']).">";
														echo "<td style='display: none'>".htmlspecialchars($row['size_id'])."</td>";
														echo "<td>".htmlspecialchars($row['size_name'])."</td>";
														echo "<td>".htmlspecialchars($row['size_description'])."</td>";
														if($_SESSION['role_id'] == 1 || $_SESSION['role_id'] == 2){
															echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary btn-lg' data-bs-toggle='modal' data-bs-target='#editSizeModal' title='Edit Task'>
                                                                        <i class='fa fa-edit'></i>
                                                                    </button>
                                                                    <button type='button' class='btn btn-link btn-danger remove-btn' data-id='".htmlspecialchars($row['size_id'])."' title='Remove'>
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
                                        <!-- Edit category modal -->
                                        <div class="modal fade" id="editSizeModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">
                                                            <span class="fw-mediumbold">
                                                            Edit</span> 
                                                            <span class="fw-light">
                                                                Size
                                                            </span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="process_editsize.php" method="POST">
                                                        <div class="modal-body">
                                                            <p class="small">Edit the size details below.</p>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Size Name</label>
                                                                        <input type="text" name="size_name" id="editSizeName" class="form-control" placeholder="fill name" required>
                                                                    </div>
                                                                </div>
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Size Description</label>
                                                                        <input type="text" name="size_description" id="editSizeDescription" class="form-control" placeholder="fill description" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="size_id" id="editSizeId">
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
                    url: 'process_getsizedata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editSizeName').val(data.size_name);
                        $('#editSizeDescription').val(data.size_description);
                        $('#editSizeId').val(data.size_id);
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