<?php 
	require ('session.php');
	require ('db.php');

    if(!isset($_GET['purchase_id'])){
        header('Location: purchase.php');
    }

	if(isset($_GET['purchase_id'])){
		$purchase_id = $_GET['purchase_id'];
	}

	$sql = "SELECT i.item_name, pi.quantity, s.size_name, pi.quantity * i.price AS item_price FROM purchase_items pi
    JOIN items i ON i.item_id = pi.item_id
    JOIN sizes s ON i.size_id = s.size_id
    WHERE purchase_id = :purchase_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':purchase_id', $_GET['purchase_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$stmt = $conn->prepare("SELECT proof_image FROM purchases WHERE purchase_id = :id");
	$stmt->execute([':id' => $purchase_id]);
	$proofImagePath = $stmt->fetchColumn();


?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<title>Purchase Log</title>
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

	<!-- CSS Just for demo purpose, don't include it in your project -->
	<link rel="stylesheet" href="assets/css/demo.css">
</head>
<body>
	<div class="wrapper">
	<?php $active = 'sales';?>
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
								<a href="#">Sales Management</a>
							</li>
							<li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="purchase.php">Purchase Log</a>
							</li>
                            <li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">View Purchase</a>
							</li>
						</ul>
					</div>
                    <div id="receiptContent">
						<div class="card p-3">
							<?php
							$totalPrice = 0;
							foreach ($data as $row) {
								$totalPrice += $row['item_price'];
							}
							?>

							<h5 class="text-end" id="totalPrice">Total Price: ₱<?php echo number_format($totalPrice, 2); ?></h5>
							<h5 class="mt-2 text-center fw-bold">House of Local</h5>

							<div class="table-responsive">
								<table class="table table-bordered" id="receipt">
									<thead>
										<tr>
											<th>Item Name</th>
											<th>Quantity</th>
											<th>Size</th>
											<th>Price</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($data as $row): ?>
											<tr>
												<td><?php echo $row['item_name']; ?></td>
												<td><?php echo $row['quantity']; ?></td>
												<td><?php echo $row['size_name']; ?></td>
												<td>₱<?php echo number_format($row['item_price'], 2); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>

							<?php if (!empty($proofImagePath) && file_exists($proofImagePath)): ?>
								<div class="text-center mt-4">
									<h6 class="fw-semibold">Proof of Payment</h6>
									<img src="<?php echo $proofImagePath; ?>" alt="Proof of Payment" class="img-fluid rounded" style="max-width: 300px; border: 1px solid #ccc;" />
								</div>
							<?php endif; ?>
						</div>
					</div>
                    <div class="d-flex justify-content-center mb-5">
						<button id="downloadPDF" class="btn btn-primary">Download Receipt PDF</button>
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
	<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
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

	<script>
		document.getElementById("downloadPDF").addEventListener("click", function () {
			const { jsPDF } = window.jspdf;
			const doc = new jsPDF({ unit: 'pt' });

			const centerX = doc.internal.pageSize.getWidth() / 2;
			const pageWidth = doc.internal.pageSize.getWidth();
			const marginRight = 40; // right margin for total amount
			const topMargin = 40;

			// TITLE
			doc.setFontSize(16);
			doc.setFont("helvetica", "bold");
			doc.text("House of Local", centerX, topMargin, { align: "center" });

			// BRANCH NAME (small, under title)
			doc.setFontSize(11);
			doc.setFont("helvetica", "normal");
			doc.text("<?php echo $branch_name; ?>", centerX, topMargin + 15, { align: "center" });

			// DATE (above table)
			doc.setFontSize(10);
			doc.text("Date: <?php echo date('F j, Y - h:i A'); ?>", 40, topMargin + 35);

			// TABLE START
			const tableStartY = topMargin + 50;

			doc.autoTable({
				startY: tableStartY,
				head: [["Item Name", "Quantity", "Size", "Price"]],
				body: [
					<?php foreach ($data as $row): ?>
						[
							"<?php echo addslashes($row['item_name']); ?>",
							"<?php echo $row['quantity']; ?>",
							"<?php echo $row['size_name']; ?>",
							"Php <?php echo number_format($row['item_price'], 2); ?>"
						],
					<?php endforeach; ?>
				],
				theme: 'grid',
				headStyles: { fillColor: [220, 220, 220] },
				styles: {
					fontSize: 10,
					cellPadding: 4
				}
			});

			// TOTAL BELOW TABLE
			const finalY = doc.lastAutoTable.finalY + 20;

			doc.setFont("helvetica", "bold");
			doc.setFontSize(11);
			// Align total amount right with marginRight
			doc.text("Total: Php <?php echo number_format($totalPrice, 2); ?>", pageWidth - marginRight, finalY, { align: "right" });

			// THANK YOU MESSAGE (centered)
			doc.setFont("helvetica", "italic");
			doc.setFontSize(11);
			doc.text("Thank you for your purchase!", centerX, finalY + 30, { align: "center" });

			doc.save("HouseOfLocal_Receipt_<?php echo date('Ymd_His'); ?>.pdf");
		});
		</script>


</body>
</html>