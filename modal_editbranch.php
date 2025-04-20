<!-- Edit Account Modal -->
<div class="modal fade" id="editBranchModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Edit</span>
                    <span class="fw-light">Branch</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_editbranch.php" method="POST">
                <div class="modal-body">
                    <p class="small">Edit the branch details below.</p>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                <label>Branch Name</label>
                                <input type="text" class="form-control" name="branch_name" id="editBranchName" placeholder="Enter branch name" required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group form-group-default">
                                <label>Location</label>
                                <input type="text" class="form-control" name="location" id="editLocation" placeholder="Enter location" required>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group form-group-default">
                                <label>Opening Time</label>
                                <input type="time" class="form-control" name="opening_time" id="editOpeningTime" required>
                            </div>
                        </div>
                        <div class="col-sm-6 ps-0">
                            <div class="form-group form-group-default">
                                <label>Closing Time</label>
                                <input type="time" class="form-control" name="closing_time" id="editClosingTime" required>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="branch_id" id="editBranchId">
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Save changes</button>    
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>