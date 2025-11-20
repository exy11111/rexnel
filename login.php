<?php 
  session_start();
  date_default_timezone_set('Asia/Manila');
  $fp_status = $_GET['fp'] ?? null;

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');
    $username = $_POST['username'];//1
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $username;
        $_SESSION['loggedin'] = true;

        $currentDateTime = date('g:i A');
        $todayDateTime = "Today at " . $currentDateTime;
        $_SESSION['last_login'] = $todayDateTime;
        $_SESSION['role_id'] = $user['role_id'];

        if($_SESSION['role_id'] == 1){
          header("Location: adminportal.php");
          exit();
        }
        else if($_SESSION['role_id'] == 3){
          $sql = "SELECT supplier_id FROM users_supplier WHERE user_id = :user_id";
          $stmt = $conn->prepare($sql);
          $stmt->bindParam(':user_id', $_SESSION['user_id']);
          $stmt->execute();
          $_SESSION['supplier_id'] = $stmt->fetchColumn();
          header("Location: supplier.php");
          exit();
        }

        $_SESSION['branch_id'] = $user['branch_id'];

        header("Location: purchase.php");
        exit();
      }
      else {
        $error_message = "Invalid password.";
      }
    }
    else {
      $error_message = "No user found with that username.";
    }
  }

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
  <link rel="icon" href="assets/img/holicon.png" type="image/x-icon"/>
</head>
<body style="background-image: url('bg.png');
    background-size: cover;
    background-repeat: no-repeat;
    background-position: center;">

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-3" style="width: 100%; max-width: 400px;">
      <div class="card-body">
      <div class="text-center mb-4">
        <img src="assets/img/lg.png" alt="Login Image" class="img-fluid" style="max-width: 150px;">
      </div>
        <h5 class="card-title text-center mb-4">Login</h5>
        <form action="" method="POST">
          <!-- Email input -->
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
            </div>
          </div>
          
          <!-- Password input -->
          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
              <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password" aria-describedby="basic-addon1">
            </div>
          </div>

          <!--<div class="mb-3 form-check">
            <input type="checkbox" class="form-check-input" id="rememberMe">
            <label class="form-check-label" for="rememberMe">Remember me</label>
          </div> -->

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary w-100 mt-3">Login</button>
        </form>
        <div class="text-center mt-3">
          <a href="#" data-bs-toggle="modal" data-bs-target="#forgot">Forgot password?</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS, Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

  <?php if (isset($error_message)): ?>
    <script>
      Swal.fire({
        icon: 'error',
        title: 'Login Failed',
        text: '<?php echo $error_message; ?>',
      });
    </script>
  <?php endif; ?>

  <!-- Modal -->
  <div class="modal fade" id="forgot" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
            
                <!-- Modal Header -->
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Forgot Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <form id="forgotForm" action="process_sfp.php" method="POST">
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <p class="small">Please fill out the email form to recover your account.</p>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text pe-3"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Email Address" required>
                            </div>
                        </div>
                    </div>
                    <!-- Modal Footer -->
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success">Continue</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

    <script>
<?php if ($fp_status === 'success'): ?>
Swal.fire({
    icon: 'success',
    title: 'Email Sent',
    text: 'A password reset link has been sent to your email.'
});
<?php elseif ($fp_status === 'user_not_found'): ?>
Swal.fire({
    icon: 'error',
    title: 'Not Found',
    text: 'No account found with that email address.'
});
<?php elseif ($fp_status === 'email_error'): ?>
Swal.fire({
    icon: 'error',
    title: 'Send Failed',
    text: 'Something went wrong while sending the email.'
});
<?php elseif ($fp_status === 'invalid_request'): ?>
Swal.fire({
    icon: 'error',
    title: 'Invalid Request',
    text: 'Please try again properly.'
});
<?php endif; ?>
</script>

<?php
if (isset($_GET['reset'])) {
    $resetStatus = $_GET['reset'];

    if ($resetStatus == 'success') {
        echo "<script>
                Swal.fire({
                    icon: 'success',
                    title: 'Password successfully reset!',
                    text: 'You can now log in with your new password.',
                    confirmButtonText: 'Login'
                }).then(() => {
                    window.location.href = 'login.php';
                });
              </script>";
    } elseif ($resetStatus == 'error') {
        echo "<script>
                Swal.fire({
                    icon: 'error',
                    title: 'Error occurred!',
                    text: 'Something went wrong. Please try again.',
                    confirmButtonText: 'Try Again'
                });
              </script>";
    }
}
?>

</body>
</html>
