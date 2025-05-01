<!-- Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-1">
            <div class="modal-header border-0">
                <h5 class="modal-title">
                    <span class="fw-mediumbold">My Profile</span> 
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3 text-center">
                    <img src="assets/img/profile.png" alt="Profile Picture" class="rounded-circle" style="width: 100px; height: 100px; object-fit: cover;">
                </div>
                <p><span class="fw-mediumbold">Username:</span> <?php echo $username?></p>
                <p><span class="fw-mediumbold">Full Name:</span> <?php echo $fullname?></p>
                <p>
                    <span class="fw-mediumbold">Email Address:</span> <?php echo $email?> 
                    <span class="float-end me-3">
                        <?php if ($is_verified): ?>
                            <i class="bi bi-patch-check-fill text-primary"></i> Verified
                        <?php else: ?>
                            <a href="#" id="resend-verification" class="text-danger text-decoration-underline">
                                <i class="bi bi-x-circle-fill"></i> Not Verified
                            </a>
                        <?php endif; ?>
                    </span>
                </p>
                <p><span class="fw-mediumbold">Account Creation Date:</span> <?php echo date("M j, Y - g:i A", strtotime($created_at)); ?></p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    $('#resend-verification').on('click', function(e) {
        Swal.fire({
            title: 'Send Verification?',
            text: 'A new verification email will be sent.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, send it',
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'process_resendverification.php',
                    method: 'POST',
                    data: { user_id: <?= $_SESSION['user_id'] ?> },
                    success: function(response) {
                        switch (response.trim()) {
                            case "success":
                                Swal.fire('Sent!', 'Verification email has been sent.', 'success');
                                break;
                            case "already_verified":
                                Swal.fire('Notice', 'This account is already verified.', 'info');
                                break;
                            case "email_error":
                                Swal.fire('Error', 'Email could not be sent. Try again later.', 'error');
                                break;
                            case "user_not_found":
                                Swal.fire('Error', 'User not found.', 'error');
                                break;
                            case "invalid_request":
                                Swal.fire('Error', 'Invalid request.', 'error');
                                break;
                            default:
                                Swal.fire('Error', 'Unexpected server response.', 'error');
                                break;
                        }
                    },
                    error: function() {
                        Swal.fire('Error!', 'Something went wrong. Try again.', 'error');
                    }
                });
            }
        });
    });
});
</script>
