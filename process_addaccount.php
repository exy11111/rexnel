<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $branch_id = $_POST['branch_id'];
    $email = $_POST['email'];

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO users (username, password, branch_id) VALUES (:username, :password, :branch_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':branch_id', $branch_id);
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
