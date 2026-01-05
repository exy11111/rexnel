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


            $ftoken = bin2hex(random_bytes(16));
            $update = $conn->prepare("UPDATE users SET ftoken = :ftoken WHERE user_id = :user_id");
            $update->execute([
                ':ftoken' => $ftoken,
                ':user_id' => $user_id
            ]);

        $reset_link = "https://houseoflocal.store/reset_password.php?ftoken=$ftoken";

        // Setup PHPMailer
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.hostinger.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'support@houseoflocal.store';
            $mail->Password = 'pM&ka&M7';
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
                    body {
                        font-family: Arial, Helvetica, sans-serif;
                        color: #333333;
                        line-height: 1.6;
                    }
                    .container {
                        max-width: 600px;
                        margin: auto;
                        padding: 20px;
                        border: 1px solid #e0e0e0;
                    }
                    .button {
                        display: inline-block;
                        padding: 12px 22px;
                        font-size: 14px;
                        color: #ffffff !important;
                        background-color: #2c3e50;
                        text-decoration: none;
                        border-radius: 4px;
                        margin-top: 10px;
                    }
                    .footer {
                        font-size: 12px;
                        color: #777777;
                        margin-top: 25px;
                    }
                </style>
            </head>
            <body>
                <div class="container">
                    <p>Dear Valued Customer,</p>

                    <p>
                        We received a request to reset the password associated with your
                        <strong>House of Local</strong> account.
                    </p>

                    <p>
                        To proceed with resetting your password, please click the button below:
                    </p>

                    <p>
                        <a href="' . $reset_link . '" class="button">Reset Password</a>
                    </p>

                    <p>
                        If the button above does not work, you may copy and paste the following link
                        into your web browser:
                    </p>

                    <p>
                        <a href="' . $reset_link . '">' . $reset_link . '</a>
                    </p>

                    <p>
                        If you did not request a password reset, please disregard this email.
                        Your account will remain secure.
                    </p>

                    <p>Thank you,</p>
                    <p>
                        <strong>House of Local Support Team</strong>
                    </p>

                    <div class="footer">
                        <p>
                            This is an automated message. Please do not reply to this email.
                        </p>
                    </div>
                </div>
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
