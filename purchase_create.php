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
		document.getElementById('barcode').addEventListener('change', function() {
			let barcode = this.value;

			fetch('process_getbarcodedata.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'barcode=' + barcode
			})
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.error
					});
					console.log(data);
					document.getElementById('item_id').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';
				} else {
					console.log(data);
					document.getElementById('item_id').value = data.item_id;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size').value = data.size_name;
					document.getElementById('price').value = data.price;
					document.getElementById('available_stock').value = data.stock;
					setTimeout(() => {
						addRow();
					}, 1000);
				}
			})
			.catch(error => console.error('Error:', error));
		});

		document.getElementById('item_id').addEventListener('change', function() {
			let itemId = this.value;

			fetch('process_getbarcodedata.php', {
				method: 'POST',
				headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
				body: 'item_id=' + itemId
			})
			.then(response => response.json())
			.then(data => {
				if (data.error) {
					Swal.fire({
						icon: 'error',
						title: 'Error',
						text: data.error
					});
					document.getElementById('barcode').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';
				} else {
					document.getElementById('barcode').value = data.barcode;
					document.getElementById('category').value = data.category_name;
					document.getElementById('brand').value = data.brand_name;
					document.getElementById('supplier').value = data.supplier_name;
					document.getElementById('size').value = data.size_name;
					document.getElementById('price').value = data.price;
					document.getElementById('available_stock').value = data.stock;
				}
			})
			.catch(error => console.error('Error:', error));
		});

		function addRow() {
			let item_id = document.getElementById("item_id").value;
			let quantity = parseFloat(document.getElementById("quantity").value) || 1;
			let available = parseFloat(document.getElementById('available_stock').value);

			if (!item_id) {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Please enter an item."
				});
				return;
			} else if (quantity < 1) {
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Please enter a valid quantity."
				});
				return;
			} else if(quantity > available){
				Swal.fire({
					icon: 'error',
					title: 'Error!',
					text: "Item out of stock."
				});
				return;
			}

			fetch("process_getreceiptdata.php?item_id=" + encodeURIComponent(item_id))
				.then(response => response.json())
				.then(data => {
					if (data.error) {
						Swal.fire({
							icon: 'error',
							title: 'Error!',
							text: data.error
						});
						return;
					}

					let table = document.getElementById("receipt").getElementsByTagName("tbody")[0];
					let rows = table.getElementsByTagName("tr");
					let pricePerUnit = parseFloat(data.price);
					let updated = false;

					// Check if item already exists
					for (let i = 0; i < rows.length; i++) {
						let existingItemId = rows[i].getElementsByTagName("td")[5].innerText;
						
						if (existingItemId === item_id) {
							let existingQuantity = parseFloat(rows[i].getElementsByTagName("td")[1].innerText);
							let newQuantity = existingQuantity + quantity;
							let newTotalPrice = newQuantity * pricePerUnit;
							
							rows[i].getElementsByTagName("td")[1].innerText = newQuantity;
							rows[i].getElementsByTagName("td")[3].innerText = "₱" + newTotalPrice.toFixed(2);
							updated = true;
							break;
						}
					}

					if (!updated) {
						let newRow = table.insertRow();
						let cell1 = newRow.insertCell(0);
						let cell2 = newRow.insertCell(1);
						let cell3 = newRow.insertCell(2);
						let cell4 = newRow.insertCell(3);
						let cell5 = newRow.insertCell(4);
						let cell6 = newRow.insertCell(5);

						cell1.innerHTML = data.item_name;
						cell2.innerHTML = quantity;
						cell3.innerHTML = data.size_name;
						cell4.innerHTML = "₱" + (quantity * pricePerUnit).toFixed(2);
						cell5.innerHTML = "<button type='button' class='btn btn-link btn-danger' title='Remove' onclick='deleteRow(this)'><i class='fa fa-times'></i></button>";
						cell6.innerHTML = item_id;
						cell6.style.display = "none";
					}

					document.getElementById("item_id").value = "";
					document.getElementById('barcode').value = '';
					document.getElementById('category').value = '';
					document.getElementById('brand').value = '';
					document.getElementById('supplier').value = '';
					document.getElementById('size').value = '';
					document.getElementById('price').value = '';
					document.getElementById('available_stock').value = '';
					document.getElementById('quantity').value = '';

					updateTotalPrice();
				})
				.catch(error => console.error("Error:", error));
		}


		document.getElementById("sbtn").addEventListener("click", function(event) {
			event.preventDefault();
			addRow();
		});

		function updateTotalPrice() {
			let total = 0;
			let table = document.getElementById("receipt");
			let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");

			for (let i = 0; i < rows.length; i++) {
				let cell4 = rows[i].getElementsByTagName("td")[3];
				let price = parseFloat(cell4.innerText.replace("₱", "").replace(",", "")) || 0;
				total += price;
			}

			document.getElementById("totalPrice").innerText = "Total Price: ₱" + total.toFixed(2);
		}

		function deleteRow(button) {
			let row = button.parentNode.parentNode;
			row.parentNode.removeChild(row);
			updateTotalPrice();
		}

		function submitReceipt() {
			let table = document.getElementById("receipt");
			let rows = table.getElementsByTagName("tbody")[0].getElementsByTagName("tr");
			let receiptData = [];
			let dateSelected = document.getElementById('startDate').value;
			let timeSelected = document.getElementById('startTime').value;

			let paymentMethod = document.querySelector("select[name='pm_id']").value;
			if (paymentMethod === "Choose Payment Method") {
				Swal.fire("Error!", "Please select a valid payment method.", "error");
				return;
			}

			let totalPriceText = document.getElementById("totalPrice").innerText;
			let totalPrice = parseFloat(totalPriceText.replace("Total Price: ₱", "").replace(",", ""));

			for (let i = 0; i < rows.length; i++) {
				let cells = rows[i].getElementsByTagName("td");

				let rowData = {
					quantity: parseInt(cells[1].innerText), // Convert to integer
					price: parseFloat(cells[3].innerText.replace("₱", "").trim()).toFixed(2), // Convert to decimal (10,2)
					item_id: parseInt(cells[5] ? cells[5].innerText : "0") // Convert to integer (default 0 if empty)
				};

				receiptData.push(rowData);
			}


			if (receiptData.length === 0) {
				Swal.fire("Error!", "Your receipt is empty. Add items before checking out.", "error");
				return;
			}

			Swal.fire({
				title: "Pay via GCash",
				html: `
					<p>Please enter your GCash reference number to confirm payment.</p>

					<input type="text"
						id="referenceNumber"
						class="swal2-input"
						placeholder="Reference Number"
						style="display:block; margin: 0 auto;">

					<p class="mt-2">Upload proof of payment (optional)</p>
					<input type="file"
						id="proofImage"
						class="swal2-file"
						accept="image/*">
				`,
				showCancelButton: true,
				confirmButtonText: "Submit",
				cancelButtonText: "Cancel",
				preConfirm: () => {
					const refInput = document.getElementById("referenceNumber").value.trim();
					const fileInput = document.getElementById("proofImage");
					const file = fileInput.files[0];

					if (!refInput) {
						Swal.showValidationMessage("You must enter the reference number.");
						return false;
					}

					// If image exists, validate type
					if (file && !file.type.startsWith("image/")) {
						Swal.showValidationMessage("Proof of payment must be an image file.");
						return false;
					}

					// If no image, return only reference number
					if (!file) {
						return { referenceNumber: refInput, proofImage: null };
					}

					// Convert image to base64
					return new Promise((resolve, reject) => {
						const reader = new FileReader();
						reader.onload = () => {
							resolve({
								referenceNumber: refInput,
								proofImage: reader.result
							});
						};
						reader.onerror = () => {
							reject("Failed to read image file.");
						};
						reader.readAsDataURL(file);
					});
				}
			}).then((result) => {
				if (result.isConfirmed && result.value) {

					fetch("process_receipt.php", {
						method: "POST",
						headers: { "Content-Type": "application/json" },
						body: JSON.stringify({
							receipt: receiptData,
							total_price: totalPrice,
							payment_method: paymentMethod,
							branch_id: <?php echo $_SESSION['branch_id']; ?>,
							reference_number: result.value.referenceNumber,
							proof_image: result.value.proofImage, // 🔥 optional
							dateSel: dateSelected,
							timeSel: timeSelected
						})
					})
					.then(response => response.json())
					.then(data => {
						if (data.success) {
							Swal.fire({
								title: "Success!",
								text: "Purchase submitted successfully!",
								icon: "success",
								confirmButtonText: "OK"
							}).then(() => {
								window.location.href = "purchase_create.php";
							});
						} else {
							Swal.fire("Error!", data.error, "error");
						}
					})
					.catch(error => console.error("Error:", error));
				}
			});

		}

	</script>
</body>
</html>
