<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $email = $_POST['email'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO users (username, password) VALUES (:username, :password)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->execute();

        $userid = $conn->lastInsertId();

        $sql2 = "INSERT INTO userdetails (user_id, firstname, lastname, email) VALUES (:user_id, :firstname, :lastname, :email)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':user_id', $userid);
        $stmt2->bindParam(':firstname', $firstname);
        $stmt2->bindParam(':lastname', $lastname);
        $stmt2->bindParam(':email', $email);
        $stmt2->execute();

        header("Location: staff.php?status=success");
        exit();

    } catch (PDOException $e) {
        header("Location: staff.php?status=error");
        exit();
    }
}
