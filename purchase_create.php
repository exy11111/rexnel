<?php 
	require ('session.php');
	require ('db.php');

	$sql1 = "SELECT * FROM items WHERE branch_id = :branch_id AND is_disabled = 0";
	$stmt1 = $conn->prepare($sql1);
	$stmt1->bindParam('branch_id', $_SESSION['branch_id']);
	$stmt1->execute();
	$data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

	$sql2 = "SELECT * FROM sizes";
	$stmt2 = $conn->prepare($sql2);
	$stmt2->execute();
	$data2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

	$sql3 = "SELECT * FROM payment_method";
	$stmt3 = $conn->prepare($sql3);
	$stmt3->execute();
	$data3 = $stmt3->fetchAll(PDO::FETCH_ASSOC);

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
								<a href="#">Purchase Log</a>
							</li>
                            <li class="separator">
								<i class="icon-arrow-right"></i>
							</li>
							<li class="nav-item">
								<a href="#">Add Purchase</a>
							</li>
						</ul>
					</div>
                    
                    <div class="row">
                        <div class="col-lg-6 col-md-12" >
                            <div class="card p-3">
                                <h5 class="mt-2">Add Purchase</h5>
                                <form id="purchaseForm" action="">
                                    <div class="mt-5 mb-3">
										<label for="barcode" class="form-label">Barcode</label>
										<input type="text" class="form-control" id="barcode" name="barcode" required>
                                    </div>
                                    <div class="mb-3">
										<label for="item_name" class="form-label">Item</label>
										<select name="" id="item_id" class="form-select" readonly>
											<option>Select Item</option>
											<?php foreach($data1 as $row):?>
												<option value="<?php echo $row['item_id']?>"><?php echo $row['item_name']?></option>
											<?php endforeach;?>
										</select>
                                    </div>
                                    <div class="mb-3">
										<label for="price" class="form-label">Price</label>
										<div class="input-group">
											<span class="input-group-text">₱</span>
											<input type="number" class="form-control" step=0.01 id="price" name="price" readonly>
										</div>
									</div>
                                    <div class="row mb-3">
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Category</label>
                                            <input type="text" class="form-control" id="category" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Brand</label>
                                            <input type="text" class="form-control" id="brand" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Supplier</label>
                                            <input type="text" class="form-control" id="supplier" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Size</label>
                                            <input type="text" class="form-control" id="size" readonly>
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Quantity</label>
                                            <input type="number" class="form-control" id="quantity">
                                        </div>
                                        <div class="col-4 mb-3">
                                            <label class="form-label">Available Quantity</label>
                                            <input type="text" class="form-control" id="available_stock" readonly>
                                        </div>
                                    </div>
    								<div class="d-flex justify-content-end">
										<button type="button" id="sbtn" class="btn btn-primary mb-2 d-none">Submit</button>
									</div>
                                </form>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card p-3">
								<div class="row">
									<div class="col-6">
										<button class="btn btn-primary mb-2" onclick="submitReceipt()">Checkout</button>
									</div>
									<div class="col-6">
										<h5 class="text-end" id="totalPrice">Total Price: ₱0.00</h5>
									</div>
									
									
								</div>
                                <h5 class="mt-2 text-center fw-bold">House of Local</h5>
								<div class="table-responsive">
                                <table class="table table-bordered" id="receipt">
                                    <thead>
                                        <tr>
                                            <th>Item Name</th>
                                            <th>Quantity</th>
                                            <th>Size</th>
                                            <th>Price</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        
                                    </tbody>
                                </table>
                            </div>
							<div class="col-md-4 mt-3">
								<div class="form-group form-group-default">
									<label>Payment Method</label>
									<select class="form-select" name="pm_id" required>
										<option>Choose Payment Method</option>
										<?php foreach ($data3 as $row):?>
											<option value="<?php echo $row['pm_id']?>"><?php echo $row['payment_method']?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<input type="date" id="startDate" class="form-control" value="<?php echo date('Y-m-d'); ?>" hidden>
								<input type="time" id="startTime" class="form-control" value="<?php echo date('H:i'); ?>" hidden>
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
<script>
document.getElementById('barcode').addEventListener('change', async function () {
	const barcode = this.value.trim();
	if (!barcode) return;

	const res = await fetch('process_getbarcodedata.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'barcode=' + encodeURIComponent(barcode)
	});

	const data = await res.json();
	if (data.error) {
		Swal.fire("Error", data.error, "error");
		clearInputs();
		return;
	}

	fillItemInputs(data);
	setTimeout(addRow, 300);
});

document.getElementById('item_id').addEventListener('change', async function () {
	const itemId = this.value;
	if (!itemId) return;

	const res = await fetch('process_getbarcodedata.php', {
		method: 'POST',
		headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
		body: 'item_id=' + encodeURIComponent(itemId)
	});

	const data = await res.json();
	if (data.error) {
		Swal.fire("Error", data.error, "error");
		clearInputs();
		return;
	}

	fillItemInputs(data);
});

function fillItemInputs(data) {
	document.getElementById('item_id').value = data.item_id;
	document.getElementById('barcode').value = data.barcode || '';
	document.getElementById('category').value = data.category_name;
	document.getElementById('brand').value = data.brand_name;
	document.getElementById('supplier').value = data.supplier_name;
	document.getElementById('size').value = data.size_name;
	document.getElementById('price').value = data.price;
	document.getElementById('available_stock').value = data.stock;
}

function clearInputs() {
	['item_id','barcode','category','brand','supplier','size','price','available_stock','quantity']
	.forEach(id => document.getElementById(id).value = '');
}
</script>
<script>
function addRow() {
	const itemId = document.getElementById("item_id").value;
	const qty = parseInt(document.getElementById("quantity").value || 1);
	const stock = parseInt(document.getElementById("available_stock").value);

	if (!itemId || qty < 1 || qty > stock) {
		Swal.fire("Error", "Invalid quantity or item.", "error");
		return;
	}

	fetch("process_getreceiptdata.php?item_id=" + itemId)
	.then(res => res.json())
	.then(data => {
		const tbody = document.querySelector("#receipt tbody");
		const rows = tbody.querySelectorAll("tr");

		for (let row of rows) {
			if (row.dataset.itemId === itemId) {
				const newQty = parseInt(row.children[1].innerText) + qty;
				row.children[1].innerText = newQty;
				row.children[3].innerText = "₱" + (newQty * data.price).toFixed(2);
				updateTotalPrice();
				clearInputs();
				return;
			}
		}

		const tr = document.createElement("tr");
		tr.dataset.itemId = itemId;
		tr.innerHTML = `
			<td>${data.item_name}</td>
			<td>${qty}</td>
			<td>${data.size_name}</td>
			<td>₱${(qty * data.price).toFixed(2)}</td>
			<td>
				<button class="btn btn-danger btn-sm" onclick="removeRow(this)">×</button>
			</td>
			<td hidden>${itemId}</td>
		`;
		tbody.appendChild(tr);

		updateTotalPrice();
		clearInputs();
	});
}

function removeRow(btn) {
	btn.closest("tr").remove();
	updateTotalPrice();
}

function updateTotalPrice() {
	let total = 0;
	document.querySelectorAll("#receipt tbody tr").forEach(row => {
		total += parseFloat(row.children[3].innerText.replace("₱",""));
	});
	document.getElementById("totalPrice").innerText = "Total Price: ₱" + total.toFixed(2);
}

document.getElementById("sbtn").addEventListener("click", e => {
	e.preventDefault();
	addRow();
});
</script>
<script>
function submitReceipt() {

	const rows = document.querySelectorAll("#receipt tbody tr");
	if (!rows.length) {
		Swal.fire("Error", "Receipt is empty.", "error");
		return;
	}

	const pm = document.querySelector("select[name='pm_id']").value;
	if (!pm || pm === "Choose Payment Method") {
		Swal.fire("Error", "Select payment method.", "error");
		return;
	}

	const receipt = [...rows].map(r => ({
		item_id: parseInt(r.children[5].innerText),
		quantity: parseInt(r.children[1].innerText),
		price: parseFloat(r.children[3].innerText.replace("₱",""))
	}));

	const total = getTotalPrice();

	Swal.fire({
		title: "Confirm Checkout?",
		icon: "warning",
		showCancelButton: true
	}).then(res => {
		if (!res.isConfirmed) return;
		pm === "1" ? cashFlow(receipt, total) : gcashFlow(receipt, total);
	});
}
</script>
<script>
function cashFlow(receipt, total) {
	Swal.fire({
		title: "Cash Payment",
		input: "number",
		preConfirm: cash => {
			if (!cash || cash < total) {
				Swal.showValidationMessage("Insufficient cash");
				return false;
			}
			return cash;
		}
	}).then(res => {
		if (!res.isConfirmed) return;
		sendToServer(receipt, total, { cash: res.value, change: res.value - total, pm_id: 1 });
	});
}

function gcashFlow(receipt, total) {
	Swal.fire({
		title: "GCash Reference",
		input: "text",
		preConfirm: ref => ref || Swal.showValidationMessage("Reference required")
	}).then(res => {
		if (!res.isConfirmed) return;
		sendToServer(receipt, total, { ref: res.value, pm_id: 2 });
	});
}
</script>
<script>
function sendToServer(receipt, total, pay) {
	fetch("process_receipt.php", {
		method: "POST",
		headers: { "Content-Type": "application/json" },
		body: JSON.stringify({
			receipt,
			total_price: total,
			payment_method: pay.pm_id,
			branch_id: <?php echo $_SESSION['branch_id']; ?>,
			dateSel: startDate.value,
			timeSel: startTime.value,
			...pay
		})
	})
	.then(res => res.json())
	.then(data => {
		if (!data.success) {
			Swal.fire("Error", data.error, "error");
			return;
		}

		Swal.fire("Success", "Printing receipt…", "success")
		.then(() => {
			printReceiptAuto(pay);
			setTimeout(() => location.href = "purchase_create.php", 500);
		});
	});
}
</script>
<script>
function printReceiptAuto(extra = {}) {

	const rows = document.querySelectorAll("#receipt tbody tr");
	if (!rows.length) {
		Swal.fire("Error", "No items to print.", "error");
		return;
	}

	let lines = [];

	// HEADER
	lines.push("HOUSE OF LOCAL");
	lines.push("");
	lines.push("--------------------------------");
	lines.push("Date: " + new Date().toLocaleString());

	const paymentSelect = document.querySelector("select[name='pm_id']");
	const paymentText = paymentSelect
		? paymentSelect.options[paymentSelect.selectedIndex].text.toUpperCase()
		: "CASH";

	lines.push("Payment: " + paymentText);

	if (extra.ref) {
		lines.push("Ref#: " + extra.ref);
	}

	lines.push("--------------------------------");
	lines.push("ITEM            QTY   AMOUNT");
	lines.push("--------------------------------");

	let total = 0;

	// ITEMS
	rows.forEach(row => {
		const td = row.querySelectorAll("td");

		const name = td[0].innerText.toUpperCase().substring(0, 15);
		const qty  = td[1].innerText;
		const amt  = td[3].innerText.replace("₱", "").trim();

		total += parseFloat(amt);

		lines.push(
			name.padEnd(15) +
			qty.padStart(3) +
			"  " +
			amt.padStart(8)
		);
	});

	// TOTALS
	lines.push("--------------------------------");
	lines.push("TOTAL:     Php " + total.toFixed(2));

	if (extra.cash !== undefined) {
		lines.push("PAID:      Php " + extra.cash.toFixed(2));
		lines.push("CHANGE:    Php " + extra.change.toFixed(2));
	}

	lines.push("--------------------------------");
	lines.push("THANK YOU FOR YOUR PURCHASE!");
	lines.push("PLEASE COME AGAIN");

	openPrintWindow(lines.join("\n"));
}
</script>
<script>
function openPrintWindow(receiptText) {

	const printWindow = window.open("", "", "width=380,height=600");

	printWindow.document.write(`
	<!DOCTYPE html>
	<html>
	<head>
		<meta charset="utf-8">
		<style>
			@page {
				margin: 0;
			}
			body {
				font-family: monospace;
				font-size: 11px;
				margin: 0;
				padding: 5px;
			}
			pre {
				white-space: pre-wrap;
				margin: 0;
			}
		</style>
	</head>
	<body>
		<pre>${receiptText}</pre>
	</body>
	</html>
	`);

	printWindow.document.close();
	printWindow.focus();
	printWindow.print();
	printWindow.onafterprint = () => printWindow.close();
}
</script>
<script>
function getTotalPrice() {
	const el = document.getElementById("totalPrice");
	if (!el) return 0;

	const text = el.innerText || "";
	return parseFloat(text.replace(/[^\d.]/g, "")) || 0;
}
</script>
</body>
</html>
