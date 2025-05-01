<?php
require 'db.php';

if (isset($_GET['vtoken'])) {
    $vtoken = $_GET['vtoken'];

    $stmt = $conn->prepare("SELECT user_id, is_verified FROM users WHERE vtoken = :vtoken");
    $stmt->bindParam(':vtoken', $vtoken);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user['is_verified']) {
            $status = 'already';
        } else {
            $update = $conn->prepare("UPDATE users SET is_verified = 1, vtoken = NULL WHERE user_id = :user_id");
            $update->execute([':user_id' => $user['user_id']]);

            if ($update->rowCount() === 1) {
                $status = 'success';
            } else {
                $status = 'fail';
            }
        }
    } else {
        $status = 'invalid';
    }
} else {
    $status = 'no_token';
}
?>
<!DOCTYPE html>
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/plugin/webfont/webfont.min.js"></script>
	<script>
		WebFont.load({
			google: {"families":["Public Sans:300,400,500,600,700"]},
			custom: {"families":["Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['assets/css/fonts.min.css']},
			active: function() {
				sessionStorage.fonts = true;
			}
		});
	</script>
    <meta charset="UTF-8">
    <title>Verification</title>
</head>
<body>
<script>
    let status = '<?= $status ?>';
    if (status === 'success') {
        Swal.fire({
            icon: 'success',
            title: 'Verified',
            text: 'Your email has been successfully verified.',
        }).then(() => {
            window.location.href = 'index.php';
        });
    } else if (status === 'already') {
        Swal.fire({
            icon: 'info',
            title: 'Already Verified',
            text: 'This email is already verified.',
        }).then(() => {
            window.location.href = 'index.php';
        });
    } else if (status === 'fail') {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Verification failed. Please try again later.',
        }).then(() => {
            window.location.href = 'index.php';
        });
    } else {
        Swal.fire({
            icon: 'warning',
            title: 'Invalid or Missing Token',
            text: 'The verification link is invalid or expired.',
        }).then(() => {
            window.location.href = 'index.php';
        });
    }
</script>
</body>
</html>
