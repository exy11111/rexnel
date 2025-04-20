<?php 
  	require ('session.php');
    require ('db.php');
    date_default_timezone_set('Asia/Manila');

    if(!isset($_SESSION['user_id']) && !$_SESSION['user_id'] == 17){ //if not admin
        header('Location: logout.php');
    }
    if(isset($_GET['id'])){
        $_SESSION['branch_id'] = $_GET['id'];
        header('Location: index.php');
        exit();
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
  <link rel="stylesheet" href="assets/css/bootstrap.min.css">
	<link rel="stylesheet" href="assets/css/plugins.min.css">
	<link rel="stylesheet" href="assets/css/kaiadmin.min.css">
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div class="card p-3" style="width: 100%; max-width: 400px;">
            <div class="card-body">
                <h5 class="card-title text-center mb-4">Choose Branch</h5>
                <div class="row">
                    <?php 
                        $sql = "SELECT * FROM branch";
                        $stmt = $conn->prepare($sql);
                        $stmt->execute();
                        $branch_data = $stmt->fetchAll();
                        foreach($branch_data as $row):
                    ?>
                    <a href="?id=<?php echo $row['branch_id']?>" class="btn btn-outline-dark mb-3"><?php echo $row['branch_name']?></a>
                    <?php endforeach; ?>
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
</body>
</html>
