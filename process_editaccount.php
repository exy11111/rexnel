<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];

    try {
        $sql = "";
        $hashedPassword = "";
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = :username, password = :password WHERE user_id = :user_id";
        }
        else{
            $sql = "UPDATE users SET username = :username WHERE user_id = :user_id";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        if (!empty($password)) {
            $stmt->bindParam(':password', $hashedPassword);
        }
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        $userid = $conn->lastInsertId();

        $sql2 = "UPDATE userdetails SET firstname = :firstname, lastname = :lastname WHERE user_id = :user_id";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':firstname', $firstname);
        $stmt2->bindParam(':lastname', $lastname);
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();

        header("Location: staff.php?editstatus=success");
        exit();

    } catch (PDOException $e) {
        header("Location: staff.php?editstatus=error");
        exit();
    }
}

?>