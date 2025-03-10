<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Account</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <!-- SweetAlert2 CSS -->
  <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
</head>
<body>

  <div class="container d-flex justify-content-center align-items-center vh-100">
    <div class="card p-3" style="width: 100%; max-width: 400px;">
      <div class="card-body">
        <h5 class="card-title text-center mb-4">Create Account</h5>
        <form action="create_account.php" method="POST">
          <!-- Username input -->
          <div class="form-group">
            <label for="username" class="form-label">Username</label>
            <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-person-fill"></i></span>
              <input type="text" class="form-control" name="username" placeholder="Username" aria-label="Username" required>
            </div>
          </div>

          <!-- Password input -->
          <div class="form-group">
            <label for="password" class="form-label">Password</label>
            <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
              <input type="password" class="form-control" name="password" placeholder="Password" aria-label="Password" required>
            </div>
          </div>

          <!-- Confirm Password input -->
          <div class="form-group">
            <label for="confirm_password" class="form-label">Confirm Password</label>
            <div class="input-group mb-3">
              <span class="input-group-text"><i class="bi bi-key-fill"></i></span>
              <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" aria-label="Confirm Password" required>
            </div>
          </div>

          <!-- Submit button -->
          <button type="submit" class="btn btn-primary w-100">Create Account</button>
        </form>
        <div class="text-center mt-3">
          Already have an account? <a href="login.php">Login here</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap 5 JS, Popper.js -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
  <!-- SweetAlert2 JS -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

</body>
</html>
