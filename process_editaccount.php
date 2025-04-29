<?php
require('session.php');
require('db.php');
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    $destination = $_POST['destination'];
    $branch_id = $_POST['branch_id'];
    $verification_token = bin2hex(random_bytes(16));

    try {
        // Check if username already exists for other users
        $sql = "SELECT username, email FROM users WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $currentData = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$currentData) {
            header("Location: " . $destination . "?editstatus=notfound");
            exit();
        }

        // Check if username is already taken by others
        $sql = "SELECT username FROM users WHERE username = :username AND user_id != :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: " . $destination . "?editstatus=exist");
            exit();
        }

        // Detect email change
        $emailChanged = ($currentData['email'] !== $email);

        // Build update query
        $sql = "UPDATE users SET username = :username, email = :email";
        $params = [
            ':username' => $username,
            ':email' => $email,
            ':user_id' => $user_id
        ];

        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql .= ", password = :password";
            $params[':password'] = $hashedPassword;
        }

        if (!empty($branch_id)) {
            $sql .= ", branch_id = :branch_id";
            $params[':branch_id'] = $branch_id;
        }

        $sql .= " WHERE user_id = :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->execute($params);

        // Update user details table
        $sql2 = "UPDATE userdetails SET firstname = :firstname, lastname = :lastname WHERE user_id = :user_id";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':firstname', $firstname);
        $stmt2->bindParam(':lastname', $lastname);
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();

        // Send verification email if email changed
        if ($emailChanged) {
            $mail = new PHPMailer(true);
            try {
                // SMTP config
                $mail->isSMTP();
                $mail->Host = 'smtp.hostinger.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'support@houseoflocal.store';
                $mail->Password = 'S8uN4#qPm2bV!rYx';
                $mail->SMTPSecure = 'ssl';
                $mail->Port = 465;
                $mail->SMTPDebug = 2;

                // Email content
                $mail->setFrom('support@houseoflocal.store', 'House of Local');
                $mail->addAddress($email, $firstname . ' ' . $lastname);
                $mail->Subject = 'Email Verification Required';
                $mail->Body = "
                        <div style='font-family: Arial, sans-serif; padding: 20px; background-color: #f8f9fa;'>
                            <h2 style='color: #343a40;'>Hi,</h2>
                            <p>Click the link below to verify your email address:</p>
                            <a href='http://houseoflocal.store/verify_email.php?token=$verification_token' 
                            style='display: inline-block; padding: 10px 20px; color: #fff; background-color: #0d6efd; 
                                    text-decoration: none; border-radius: 5px;'>
                            Verify Email
                            </a>
                            <p style='margin-top: 20px;'>Thank you.</p>
                        </div>
                        ";

                $mail->send();
                // Optional: Set a flag in DB for unverified email
            } catch (Exception $e) {
                error_log("Mail error: " . $mail->ErrorInfo);
            }
        }

        header("Location: " . $destination . "?editstatus=success");
        exit();
    } catch (PDOException $e) {
        error_log("DB Error: " . $e->getMessage());
        header("Location: " . $destination . "?editstatus=error");
        exit();
    }
}
?>
