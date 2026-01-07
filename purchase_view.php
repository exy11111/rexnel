<?php 
	require ('session.php');
	require ('db.php');

    if(!isset($_GET['purchase_id'])){
        header('Location: purchase.php');
    }

	if(isset($_GET['purchase_id'])){
		$purchase_id = $_GET['purchase_id'];
	}

	$sql = "
	SELECT 
		i.item_name,
		pi.quantity,
		s.size_name,

		/* item_price = line total (discount-aware) */
		pi.quantity *
		(
			CASE
				WHEN pi.is_discounted = 1 
					AND pi.unit_price IS NOT NULL
				THEN pi.unit_price
				ELSE i.price
			END
		) AS item_price,

		pi.is_discounted

	FROM purchase_items pi
	JOIN items i ON i.item_id = pi.item_id
	JOIN sizes s ON i.size_id = s.size_id
	WHERE pi.purchase_id = :purchase_id
	";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':purchase_id', $_GET['purchase_id']);
    $stmt->execute();
    $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

	$sql = "SELECT cash, change_cash, ref, pm_id FROM purchases WHERE purchase_id = :purchase_id";
	$stmt = $conn->prepare($sql);
    $stmt->bindParam(':purchase_id', $_GET['purchase_id']);
    $stmt->execute();
    $cash = $stmt->fetch(PDO::FETCH_ASSOC);

	if($cash['pm_id'] == 1){
		$payment_method = "Cash";
	}
	else{
		$payment_method = "GCash";
	}

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

												<td class="<?php echo $row['is_discounted'] ? 'text-warning fw-bold text-nowrap' : 'text-nowrap'; ?>">
													<?php if ($row['is_discounted']): ?>
														<i class="fa fa-tag me-1"></i>
													<?php endif; ?>
													₱<?php echo number_format($row['item_price'], 2); ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
							<h5 class="text-end" id="totalPrice">Total Price: ₱<?php echo number_format($totalPrice, 2); ?></h5>
							<h5 class="text-end" id="totalPaid">Paid <?php echo $payment_method?>: ₱<?php echo number_format($cash['cash'], 2); ?></h5>
							<?php if ($cash['pm_id'] == 1):?>
							<h5 class="text-end" id="totalChange">Change: ₱<?php echo number_format($cash['change_cash'], 2); ?></h5>
							<?php else: ?>
							<h5 class="text-end" id="totalChange">Ref #: <?php echo $cash['ref']; ?></h5>
							<?php endif; ?>

							<?php if (!empty($proofImagePath) && file_exists($proofImagePath)): ?>
								<div class="text-center mt-4">
									<h6 class="fw-semibold">Proof of Payment</h6>
									<img src="<?php echo $proofImagePath; ?>" alt="Proof of Payment" class="img-fluid rounded" style="max-width: 300px; border: 1px solid #ccc;" />
								</div>
							<?php endif; ?>
						</div>
					</div>
                    <div class="d-flex justify-content-center mb-5">
						<div class="row">
							<div class="col">
								<button id="downloadPDF" class="btn btn-primary">Download Receipt PDF</button>
								<button id="printReceipt" class="btn btn-secondary">
									Print as Receipt
								</button>
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
(function () {
	const { jsPDF } = window.jspdf;

	function generateReceiptPDF(mode) {
		const doc = new jsPDF({ unit: "pt" });

		const pageWidth = doc.internal.pageSize.getWidth();
		const centerX = pageWidth / 2;
		const marginRight = 40;
		const topMargin = 40;

		// ======================
		// HEADER
		// ======================
		doc.setFont("helvetica", "bold");
		doc.setFontSize(16);
		doc.text("House of Local", centerX, topMargin, { align: "center" });

		doc.setFont("helvetica", "normal");
		doc.setFontSize(11);
		doc.text("<?php echo $branch_name; ?>", centerX, topMargin + 15, { align: "center" });

		doc.setFontSize(10);
		doc.text("Date: <?php echo date('F j, Y - h:i A'); ?>", 40, topMargin + 35);

		// ======================
		// TABLE
		// ======================
		doc.autoTable({
			startY: topMargin + 50,
			head: [["Item Name", "Qty", "Size", "Price"]],
			body: [
				<?php foreach ($data as $row): ?>
				[
					"<?php echo addslashes($row['item_name']); ?>",
					"<?php echo $row['quantity']; ?>",
					"<?php echo $row['size_name']; ?>",
					"<?php echo ($row['is_discounted'] ? '🏷 ' : ''); ?>₱<?php echo number_format($row['item_price'], 2); ?>"
				],
				<?php endforeach; ?>
			],
			theme: "grid",
			headStyles: { fillColor: [0, 0, 0], textColor: [255, 255, 255] },
			styles: { fontSize: 10, cellPadding: 4 }
		});

		// ======================
		// TOTALS
		// ======================
		let finalY = doc.lastAutoTable.finalY + 20;

		doc.setFont("helvetica", "bold");
		doc.setFontSize(11);
		doc.text(
			"Total: ₱<?php echo number_format($totalPrice, 2); ?>",
			pageWidth - marginRight,
			finalY,
			{ align: "right" }
		);

		finalY += 15;
		doc.text(
			"Paid (<?php echo $cash['pm_id'] == 1 ? 'Cash' : 'GCash'; ?>): ₱<?php echo number_format($cash['cash'], 2); ?>",
			pageWidth - marginRight,
			finalY,
			{ align: "right" }
		);

		finalY += 15;

		<?php if ($cash['pm_id'] == 1): ?>
			doc.text(
				"Change: ₱<?php echo number_format($cash['change_cash'], 2); ?>",
				pageWidth - marginRight,
				finalY,
				{ align: "right" }
			);
		<?php else: ?>
			doc.text(
				"GCash Ref #: <?php echo $cash['ref']; ?>",
				pageWidth - marginRight,
				finalY,
				{ align: "right" }
			);
		<?php endif; ?>

		// ======================
		// PROOF OF PAYMENT
		// ======================
		<?php if (!empty($proofImagePath) && file_exists($proofImagePath)): ?>
			const img = new Image();
			img.src = "<?php echo $proofImagePath; ?>";
			img.onload = function () {
				const imgWidth = 200;
				const imgHeight = (img.height / img.width) * imgWidth;
				const posY = finalY + 40;

				doc.setFont("helvetica", "bold");
				doc.setFontSize(11);
				doc.text("Proof of Payment", centerX, posY, { align: "center" });

				doc.addImage(
					img,
					"JPEG",
					centerX - imgWidth / 2,
					posY + 10,
					imgWidth,
					imgHeight
				);

				doc.setFont("helvetica", "italic");
				doc.setFontSize(11);
				doc.text(
					"Thank you for your purchase!",
					centerX,
					posY + imgHeight + 40,
					{ align: "center" }
				);

				finalizePDF(doc, mode);
			};
		<?php else: ?>
			doc.setFont("helvetica", "italic");
			doc.setFontSize(11);
			doc.text(
				"Thank you for your purchase!",
				centerX,
				finalY + 30,
				{ align: "center" }
			);
			finalizePDF(doc, mode);
		<?php endif; ?>
	}

	function finalizePDF(doc, mode) {
		if (mode === "print") {
			doc.autoPrint();
			const blobUrl = doc.output("bloburl");
			window.open(blobUrl, "_blank");
		} else {
			doc.save("HouseOfLocal_Receipt_<?php echo date('Ymd_His'); ?>.pdf");
		}
	}

	// ======================
	// BUTTON HANDLERS
	// ======================
	document.getElementById("downloadPDF").addEventListener("click", function () {
		generateReceiptPDF("download");
	});

	document.getElementById("printReceipt").addEventListener("click", function () {
		generateReceiptPDF("print");
	});
})();
</script>



</body>
</html>