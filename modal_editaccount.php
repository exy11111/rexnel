<!-- Edit Account Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">Edit</span> 
                    <span class="fw-light">Account</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="process_editaccount.php" method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <p class="small">Edit the account details below.</p>
                    <div class="row">
                        <?php if(isset($branch_data)):?>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Branch</label>
                                <select name="branch_id" id="editBranchId" class="form-select">
                                    <option value="">Select Branch</option>
                                    <?php foreach($branch_data as $row):?>
                                        <option value="<?php echo $row['branch_id']?>"><?php echo $row['branch_name']?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                        <?php endif; ?>
                        <div class="col-sm-12 mb-2 text-center">
                            <img id="previewProfilePhoto" 
                                src="<?php echo !empty($profile_photo) ? $profile_photo : 'assets/img/profile.png'; ?>" 
                                alt="Current Photo" 
                                class="rounded-circle" 
                                width="100">
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Profile Photo</label>
                                <input type="file" class="form-control" name="profile_photo" accept="image/*">
                                <small class="text-muted">Accepted formats: JPG, PNG, JPEG (Max: 2MB)</small>
                            </div>
                        </div>
                        <script>
                            // Preview the selected image
                            document.querySelector("input[name='profile_photo']").addEventListener('change', function(e) {
                                const reader = new FileReader();
                                reader.onload = function(event) {
                                    document.getElementById('previewProfilePhoto').src = event.target.result;
                                }
                                reader.readAsDataURL(e.target.files[0]);
                            });
                        </script>

                        <div class="col-md-6 pe-0">
                            <div class="form-group form-group-default">
                                <label>First Name</label>
                                <input type="text" class="form-control" name="firstname" id="editFirstName" placeholder="Enter first name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group form-group-default">
                                <label>Last Name</label>
                                <input type="text" class="form-control" name="lastname" id="editLastName" placeholder="Enter last name" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Email</label>
                                <input type="email" class="form-control" name="email" id="editEmail" placeholder="Enter email" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group form-group-default">
                                <label>Username</label>
                                <input type="text" class="form-control" name="username" id="editUsername" placeholder="Enter username" required>
                            </div>
                        </div>
                        <div class="col-sm-12">
                        <div class="form-group form-group-default">
                            <label>Password</label>
                            <input type="password" class="form-control" name="password" placeholder="fill password">
                        </div>
                    </div>
                        <input type="hidden" name="user_id" id="editUserId">
                        <input type="hidden" name="destination" id="editDestination">
                        <script>
                            document.getElementById("editDestination").value = window.location.href;
                        </script>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="submit" class="btn btn-primary">Save changes</button>    
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>