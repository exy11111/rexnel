<?php 
    require 'PHPMailer/src/Exception.php';
    require 'PHPMailer/src/PHPMailer.php';
    require 'PHPMailer/src/SMTP.php';
    require 'db.php';
    require 'session.php';

    use PHPMailer\PHPMailer\PHPMailer;
    use PHPMailer\PHPMailer\Exception;
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];

        $stmt = $conn->prepare("SELECT email, is_verified FROM users JOIN userdetails ON users.user_id = userdetails.user_id WHERE users.user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() === 1) {
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            $is_verified = $row['is_verified'];
            $email = $row['email'];

            if ($is_verified) {
                echo "already_verified";
                exit;
            }

            $vtoken = bin2hex(random_bytes(16));
            $stmt = $conn->prepare("UPDATE users SET vtoken = :vtoken WHERE user_id = :user_id");
            $stmt->execute([
                ':user_id' => $user_id,
                ':vtoken' => $vtoken
            ]);
            $verify_link = "https://houseoflocal.store/process_verify.php?vtoken=$vtoken";
            $mail = new PHPMailer;
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
            $mail->Subject = 'Email Verification Required - House of Local';

            $mail->Body = '
            <html>
            <head>
            <style>
                .button {
                display: inline-block;
                padding: 10px 18px;
                font-size: 14px;
                color: #fff;
                background-color: #2c3e50;
                text-decoration: none;
                border-radius: 4px;
                }
            </style>
            </head>
            <body>
            <p>This email address needs to be verified for security purposes.</p>
            <p><a href="' . $verify_link . '" class="button">Verify Email</a></p>
            <p>If the button doesn\'t work, open this link in your browser:</p>
            <p><a href="' . $verify_link . '">' . $verify_link . '</a></p>
            </body>
            </html>
            ';
            
            if ($mail->send()) {
                echo "success";
            } else {
                echo "email_error";
            }


        }
        else {
            echo "user_not_found";
        }
    }
    else {
        echo "invalid_request";
    }
    ob_end_flush();



?>