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
          <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <input type="password" name="new_password" id="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <input type="password" name="confirm_password" id="confirm_password" class="form-control" required>
          </div>
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

</body>
</html>
