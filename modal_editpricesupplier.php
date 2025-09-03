<!-- Modal -->
<div class="modal modal-lg fade" id="editPriceModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">
                    Edit</span> 
                    <span class="fw-light">
                        Price
                    </span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_editprice_supplier.php" method="POST">
                <div class="modal-body">
                    <p class="small">Edit the price below.</p>
                    <div class="row">		
                        <div class="col-sm-12">
                            <div class="d-flex align-items-center">
                                <div class="form-group form-group-default flex-grow-1">
                                    <label for="price" class="form-label">Price (₱)</label>
                                    <input type="number" class="form-control" id="price" name="price" placeholder="Enter amount" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                    <input type="text" name="item_id" id="editItemId" hidden>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Save Changes</button>
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>