<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $supplier_id = $_POST['supplier_id'];
    $email = $_POST['email'];
    $role_id = 3;
    $branch_id = 0;

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    try {
        $sql = "INSERT INTO users (username, password, branch_id, role_id) VALUES (:username, :password, :branch_id, :role_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':role_id', $role_id);
        $stmt->execute();

        $userid = $conn->lastInsertId();

        $sql2 = "INSERT INTO userdetails (user_id, firstname, lastname, email) VALUES (:user_id, :firstname, :lastname, :email)";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':user_id', $userid);
        $stmt2->bindParam(':firstname', $firstname);
        $stmt2->bindParam(':lastname', $lastname);
        $stmt2->bindParam(':email', $email);
        $stmt2->execute();

        $sql = "INSERT INTO users_supplier (user_id, supplier_id) VALUES (:user_id, :supplier_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':user_id', $userid);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->execute();

        header("Location: supplieraccount.php?status=success");
        exit();

    } catch (PDOException $e) {
        header("Location: supplieraccount.php?status=error");
        exit();
    }
}
