<?php 
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'db.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
    $email = $_POST['email'];

    // Check if email exists
    $stmt = $conn->prepare("SELECT users.user_id, ftoken FROM users JOIN userdetails ON users.user_id = userdetails.user_id WHERE userdetails.email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['user_id'];
        $ftoken = $row['ftoken'];

        // Generate token if it doesn't exist
        if (empty($ftoken)) {
            $ftoken = bin2hex(random_bytes(16));
            $update = $conn->prepare("UPDATE users SET ftoken = :ftoken WHERE user_id = :user_id");
            $update->execute([
                ':ftoken' => $ftoken,
                ':user_id' => $user_id
            ]);
        }

        $reset_link = "https://houseoflocal.store/reset_password.php?ftoken=$ftoken";

        // Setup PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'support@houseoflocal.store';
            $mail->Password = 'S8uN4#qPm2bV!rYx';
            $mail->SMTPSecure = 'ssl';
            $mail->Port = 465;

            $mail->setFrom('support@houseoflocal.store', 'House of Local');
            $mail->addAddress($email);
            $mail->isHTML(true);
            $mail->Subject = 'Reset Your Password - House of Local';
            $mail->Body = '
                <html>
                <head>
                <style>
                    .button {
                        display: inline-block;
                        padding: 10px 18px;
                        font-size: 14px;
                        color: white;
                        background-color: #2c3e50;
                        text-decoration: none;
                        border-radius: 4px;
                    }
                </style>
                </head>
                <body>
                    <p>Click the button below to reset your password:</p>
                    <p><a href="' . $reset_link . '" class="button">Reset Password</a></p>
                    <p>If the button doesn\'t work, copy and paste this link into your browser:</p>
                    <p><a href="' . $reset_link . '">' . $reset_link . '</a></p>
                </body>
                </html>
            ';

            $mail->send();
            header("Location: login.php?fp=success");
            exit;

        } catch (Exception $e) {
            header("Location: login.php?fp=email_error");
            exit;
        }

    } else {
        header("Location: login.php?fp=user_not_found");
        exit;
    }

} else {
    header("Location: login.php?fp=invalid_request");
    exit;
}
?>
