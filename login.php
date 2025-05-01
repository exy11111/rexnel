<?php 
  session_start();
  date_default_timezone_set('Asia/Manila');

  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    include('db.php');
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
      $user = $stmt->fetch(PDO::FETCH_ASSOC);

      if (password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['loggedin'] = true;

        $currentDateTime = date('g:i A');
        $todayDateTime = "Today at " . $currentDateTime;
        $_SESSION['last_login'] = $todayDateTime;
        $_SESSION['role_id'] = $user['role_id'];

        if($_SESSION['user_id'] == 17){
          header("Location: adminportal.php");
          exit();
        }
        $_SESSION['branch_id'] = $user['branch_id'];

        header("Location: index.php");
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
<body>

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-3" style="width: 100%; max-width: 400px;">
      <div class="card-body">
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
                
                <form id="forgotForm">
                    <!-- Modal Body -->
                    <div class="modal-body">
                        <p class="small">Please fill out the email form to recover your account.</p>
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text pe-3"><i class="bi bi-envelope-fill"></i></span>
                                <input type="email" name="email" class="form-control" placeholder="Email Address" required>
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
document.getElementById('forgotForm').addEventListener('submit', function(e) {
    e.preventDefault(); // Prevent form from submitting normally

    const form = e.target;
    const formData = new FormData(form);

    fetch('process_sfp.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        let icon = 'error';
        let title = 'Error';
        let text = '';

        if (result === 'success') {
            icon = 'success';
            title = 'Email Sent';
            text = 'A password reset link has been sent to your email.';
        } else if (result === 'user_not_found') {
            text = 'No account found with that email address.';
        } else if (result === 'email_error') {
            text = 'Something went wrong while sending the email.';
        } else {
            text = 'Invalid request.';
        }

        Swal.fire({
            icon: icon,
            title: title,
            text: text
        });

        if (result === 'success') {
            form.reset(); // Clear form if successful
            const modal = bootstrap.Modal.getInstance(document.getElementById('forgot'));
            modal.hide(); // Close modal
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Something went wrong. Please try again.'
        });
    });
});
</script>

</body>
</html>
