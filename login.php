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

        if($_SESSION['user_id'] == 17){
          header("Location: adminportal.php");
          exit();
        }

        $currentDateTime = date('g:i A');
        $todayDateTime = "Today at " . $currentDateTime;
        $_SESSION['last_login'] = $todayDateTime;

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
        <!-- <div class="text-center mt-3">
          <a href="#">Forgot password?</a>
        </div>-->
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
</body>
</html>
