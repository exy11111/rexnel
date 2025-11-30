<?php
require 'db.php';

$showModal = false;
$ftoken = '';

if (isset($_GET['ftoken'])) {
    $ftoken = $_GET['ftoken'];

    $stmt = $conn->prepare("SELECT user_id FROM users WHERE ftoken = :ftoken");
    $stmt->bindParam(':ftoken', $ftoken);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $showModal = true;
    } else {
        // Store the error message for later display using PHP
        $error_message = "Invalid or Expired Link. Please request a new password reset link.";
    }
} else {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>

<?php if ($showModal): ?>
<!-- Password Reset Modal -->
<div class="modal show d-block" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <form action="process_reset.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title">Create New Password</h5>
        </div>
        <div class="modal-body">
          <input type="hidden" name="ftoken" value="<?= htmlspecialchars($ftoken) ?>">
         <div class="input-group mb-3">
            <input type="password" 
                class="form-control password-field" 
                name="new_password" 
                placeholder="Fill password" 
                required>
            <span class="input-group-text toggle-password" style="cursor:pointer;">
                <i class="bi bi-eye-slash-fill"></i>
            </span>
        </div>

        <div class="input-group mb-3">
            <input type="password" 
                class="form-control password-field" 
                name="confirm_password" 
                placeholder="Confirm password" 
                required>
            <span class="input-group-text toggle-password" style="cursor:pointer;">
                <i class="bi bi-eye-slash-fill"></i>
            </span>
        </div>
        <div class="modal-footer">
          <a href="login.php" class="btn btn-secondary">Cancel</a>
          <button type="submit" class="btn btn-success">Update Password</button>
        </div>
      </form>
    </div>
  </div>
</div>
<?php endif; ?>

<?php if (isset($error_message)): ?>
<!-- Show SweetAlert for invalid/expired link -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    Swal.fire({
        icon: 'error',
        title: 'Error!',
        text: '<?= $error_message ?>',
        confirmButtonText: 'Go to Login'
    }).then(() => {
        window.location.href = 'login.php';
    });
</script>
<?php endif; ?>

<script>
    // Select all toggle-password buttons
    document.querySelectorAll('.toggle-password').forEach(function(toggle) {
        toggle.addEventListener('click', function() {
            const input = this.previousElementSibling; // the input before the span
            const icon = this.querySelector('i');
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove("bi-eye-slash-fill");
                icon.classList.add("bi-eye-fill");
            } else {
                input.type = "password";
                icon.classList.remove("bi-eye-fill");
                icon.classList.add("bi-eye-slash-fill");
            }
        });
    });
</script>

</body>
</html>
