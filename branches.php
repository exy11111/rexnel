<?php 
	require ('session.php');
	require ('db.php');

	if($_SESSION['user_id'] != 17){
		header('Location: index.php?access=denied');
		exit();
	}

    $sql = "SELECT * FROM branch";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $branch_data1 = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Branches</title>
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
								<a href="#">Branches</a>
							</li>
						</ul>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title">Branches</h4>
										<?php if($_SESSION['user_id'] == 17):?>
										<button class="btn btn-primary btn-round ms-auto" data-bs-toggle="modal" data-bs-target="#addAccountModal">
											<i class="fa fa-plus"></i>
											Add Branch
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
															Branch
														</span>
													</h5>
													<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
												</div>
                                                <form action="process_addbranch.php" method="POST">
												    <div class="modal-body">
													    <p class="small">Create a new branch using this form, make sure you fill them all</p>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Branch Name</label>
                                                                    <input type="text" class="form-control" name="branch_name" placeholder="fill branch name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-12">
                                                                <div class="form-group form-group-default">
                                                                    <label>Location</label>
                                                                    <input type="text" class="form-control" name="location" placeholder="fill location" required>
                                                                </div>
                                                            </div>
															<div class="col-md-6 ">
                                                                <div class="form-group form-group-default">
                                                                    <label>Opening Time</label>
                                                                    <input type="time" class="form-control" name="opening_time" value="08:30" required>
                                                                </div>
                                                            </div>
															<div class="col-md-6 ps-0">
                                                                <div class="form-group form-group-default">
                                                                    <label>Closing Time</label>
                                                                    <input type="time" class="form-control" name="closing_time" value="22:30" required>
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
										<table id="accounts" class="display table table-striped table-hover">
											<thead>
												<tr>
													<th>ID</th>
													<th>Branch Name</th>
													<th>Location</th>
													<th>Operating Hours</th>
													<?php if($_SESSION['user_id'] == 17):?>
													<th style="width: 10%">Action</th>
													<?php endif; ?>
												</tr>
											</thead>
											<tbody>
                                                <?php
                                                    foreach ($branch_data1 as $row) {
                                                        echo "<tr data-id=".htmlspecialchars($row['branch_id']).">";
														echo "<td>". htmlspecialchars($row['branch_id']) . "</td>";
                                                        echo "<td>". htmlspecialchars($row['branch_name']) . "</td>";
														echo "<td>" . htmlspecialchars($row['location']) . "</td>";
														echo "<td>" . date("g:iA", strtotime($row['opening_time'])) . " to " . date("g:iA", strtotime($row['closing_time'])) . "</td>";
                                                        if($_SESSION['user_id'] == 17){
															echo "<td>
                                                                <div class='form-button-action'>
                                                                    <button type='button' class='btn btn-link btn-primary btn-lg' data-bs-toggle='modal' data-bs-target='#editBranchModal' title='Edit Task'>
                                                                        <i class='fa fa-edit'></i>
                                                                    </button>
                                                                    <button type='button' class='btn btn-link btn-danger remove-btn' data-id='".htmlspecialchars($row['branch_id'])."' title='Remove'>
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
                                                        const branchId = this.getAttribute('data-id');
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
                                                                xhr.open('POST', 'process_deletebranch.php', true);
                                                                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                                                                xhr.onload = function() {
                                                                    if (xhr.status === 200) {
                                                                        if (xhr.responseText === 'success') {
                                                                            Swal.fire('Deleted!', 'The branch has been deleted.', 'success').then(() => {
                                                                                window.location.href = 'branches.php';
                                                                            });
                                                                        } else {
                                                                            Swal.fire('Error!', 'There was an error deleting the branch.', 'error');
                                                                        }
                                                                    }
                                                                };
                                                                xhr.send('branch_id=' + branchId);
                                                            }
                                                        });
                                                    });
                                                });
                                            });

                                        </script>
                                        <?php include 'modal_editbranch.php'; ?>
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
	<script src="assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>	
	<!-- Datatables -->
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
		});
	</script>

	<!-- Auto populate in edit modal -->
    <script>
        $(document).ready(function() {
            $('#accounts').on('click', '.btn-link.btn-primary', function() {
                var row = $(this).closest('tr');
                var id = row.data('id');
                $.ajax({
                    url: 'process_getbranchdata.php',
                    type: 'GET',
                    data: { id: id },
                    dataType: 'json',
                    success: function(data) {
                        $('#editBranchName').val(data.branch_name);
                        $('#editLocation').val(data.location);
                        $('#editOpeningTime').val(data.opening_time);
                        $('#editClosingTime').val(data.closing_time);
						$('#editBranchId').val(data.branch_id);
                        $('#editBranchModal').modal('show');
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
                    title: 'Branch Added!',
                    text: 'The branch has been successfully created.',
                }).then((result) => {
                });
            <?php elseif ($_GET['status'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while creating the branch.',
                });
			<?php elseif ($_GET['status'] == 'exist'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Branch name already exists.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

    <?php if (isset($_GET['editstatus'])): ?>
        <script>
            <?php if ($_GET['editstatus'] == 'success'): ?>
                Swal.fire({
                    icon: 'success',
                    title: 'Branch Edited!',
                    text: 'The branch has been successfully edited.',
                }).then((result) => {
                });
            <?php elseif ($_GET['editstatus'] == 'error'): ?>
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: 'Something went wrong while editing the branch.',
                });
            <?php endif; ?>
        </script>
    <?php endif; ?>

	
</body>
</html>