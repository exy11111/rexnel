<?php 
	require ('session.php');
	require ('db.php');

	$sql = "SELECT category_id, category_name FROM categories";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Categories</title>
	<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
	<link rel="icon" href="assets/img/kaiadmin/favicon.ico" type="image/x-icon"/>

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
		<div class="sidebar" data-background-color="dark">
			<div class="sidebar-logo">
				<!-- Logo Header -->
				<div class="logo-header" data-background-color="dark">

					<a href="index.php" class="logo">
						<img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20">
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
						<li class="nav-item active">
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

						<a href="index.php" class="logo">
							<img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20">
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
						<h3 class="fw-bold mb-3">Categories</h3>
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
								<a href="#">Inventory Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Categories</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Categories</h4>
										<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addRowModal">
											<i class="fa fa-plus"></i>
											Add Category
										</button>
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
															Category
														</span>
													</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
                                                <form action="process_addcategory.php" method="POST">
                                                    <div class="modal-body">
                                                        <p class="small">Create a new category using this form, make sure you fill them all</p>
                                                        <div class="row">
                                                            <div class="col-sm-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Category Name</label>
                                                                    <input type="text" name="category_name" class="form-control" placeholder="fill name" required>
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
										<table id="categories" class="display table table-striped table-hover" >
											<thead>
												<tr>
													<th style="width: 20%">Category ID</th>
													<th>Category Name</th>
													<th style="width: 10%">Action</th>
												</tr>
											</thead>
											<tbody>
												<?php 
													foreach($data as $row){
														echo "<tr data-id=".htmlspecialchars($row['category_id']).">";
														echo "<td>".htmlspecialchars($row['category_id'])."</td>";
														echo "<td>".htmlspecialchars($row['category_name'])."</td>";
														echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary btn-lg' data-bs-toggle='modal' data-bs-target='#editCategoryModal' title='Edit Task'>
                                                                        <i class='fa fa-edit'></i>
                                                                    </button>
                                                                    <button type='button' class='btn btn-link btn-danger remove-btn' data-id='".htmlspecialchars($row['category_id'])."' title='Remove'>
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
                                                        const categoryId = this.getAttribute('data-id');
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
                                                                xhr.open('POST', 'process_deletecategory.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The category has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'categories.php';
                                                                            });
                                                                        } else if(xhr.responseText === 'exist'){
																			Swal.fire({
																				title: 'Items in this category will also be deleted.',
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
																					xhr1.open('POST', 'process_confirmdeletecategory.php', true);
																					xhr1.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
																					xhr1.onload = function() {
																						if (xhr1.status === 200) {
																							if (xhr1.responseText === 'success') {
																								Swal.fire('Deleted!', 'The category has been deleted.', 'success').then(() => {
																									window.location.href = 'categories.php';
																								});
																							}
																						}
																					}
																					xhr1.send('category_id=' + categoryId);
																				}
																			});
																		}else {
                                                                            Swal.fire('Error!', 'There was an error deleting the category.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('category_id=' + categoryId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
                                        <!-- Edit category modal -->
                                        <div class="modal fade" id="editCategoryModal" tabindex="-1" role="dialog" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header border-0">
                                                        <h5 class="modal-title">
                                                            <span class="fw-mediumbold">
                                                            Edit</span> 
                                                            <span class="fw-light">
                                                                Category
                                                            </span>
                                                        </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <form action="process_editcategory.php" method="POST">
                                                        <div class="modal-body">
                                                            <p class="small">Edit the category details below.</p>
                                                            <div class="row">
                                                                <div class="col-sm-12">
                                                                    <div class="form-group form-group-default">
                                                                        <label>Category Name</label>
                                                                        <input type="text" name="category_name" id="editCategoryName" class="form-control" placeholder="fill name" required>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <input type="hidden" name="category_id" id="editCategoryId">
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
	<script >
		$(document).ready(function() {
			

			// Add Row
			$('#categories').DataTable({
				"pageLength": 10,
			});

			var action = '<td> <div class="form-button-action"> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-primary btn-lg" data-original-title="Edit Task"> <i class="fa fa-edit"></i> </button> <button type="button" data-bs-toggle="tooltip" title="" class="btn btn-link btn-danger" data-original-title="Remove"> <i class="fa fa-times"></i> </button> </div> </td>';

			$('#addRowButton').click(function() {
				$('#categories').dataTable().fnAddData([
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
            $('#categories').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getcategorydata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editCategoryName').val(data.category_name);
                        $('#editCategoryId').val(data.category_id);
                        $('#editCategoryModal').modal('show');
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
                    title: 'Category Added!',
                    text: 'The category has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the category.',
                });
            <?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Category already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Category Edited!',
                    text: 'The category has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the category.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>
</body>
</html>