<!-- Modal -->
<div class="modal modal-lg fade" id="viewItemModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    View</span> 
                    <span class="fw-light">
                        Item Information
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row">		
                    <div class="col-sm-12">
                        
                    </div>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal modal-lg fade" id="editStatusModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Edit</span> 
                    <span class="fw-light">
                        Status
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_editstatus_supplier.php" method="POST">
                <div class="modal-body">
                    <p class="small">Edit the Status below.</p>
                    <div class="row">		
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label for="category">Status</label>
                                <select class="form-select" id="editStatus" name="status" required>
                                    <option value="">Select Status</option>
                                    <option value="Pending">Pending</option>
                                    <option value="Accepted">Accepted</option>
                                    <option value="Shipping">Shipping</option>
                                    <option value="Delivered">Delivered</option>
                                    <option value="Received">Received</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="order_id" id="editOrderId" hidden>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    var viewItemModal = document.getElementById('viewItemModal');
    var editStatusModal = document.getElementById('editStatusModal');

    viewItemModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var itemId = button.getAttribute('data-id'); 
        
        viewItemModal.querySelector('.modal-body .col-sm-12').innerHTML = `<p>Loading...</p>`;
        
        fetch('process_getitem1.php?id=' + itemId)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                viewItemModal.querySelector('.modal-body .col-sm-12').innerHTML = `
                    <div class='row'>
                        <div class='col-6'>
                            <p><strong>Item Name:</strong> ${data.item_name}</p>
                            <p><strong>Category:</strong> ${data.category_name}</p>
                            <br>
                            <p><strong>Quantity:</strong> ${data.quantity}</p>
                            <p><strong>Amount:</strong> ₱${data.amount}</p>
                        </div>
                        <div class='col-6'>
                            <p><strong>Brand:</strong> ${data.brand_name}</p>
                            <p><strong>Size:</strong> ${data.size_name}</p>
                        </div>
                    </div>		
                `;
            })
            .catch(error => {
                console.error('Error:', error);
                viewItemModal.querySelector('.modal-body .col-sm-12').innerHTML = `<p class="text-danger">Failed to load item data.</p>`;
            });
    });

    editStatusModal.addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; 
        var orderId = button.getAttribute('data-id'); 
        document.getElementById("editOrderId").value = orderId;

        fetch('process_fetchstatussupplier.php?id=' + orderId)
            .then(response => response.json())
            .then(data => {
                console.log(data);
                document.getElementById("editStatus").value = data.status;
            })
            .catch(error => {
                console.error('Error:', error);
        });

    });
</script>