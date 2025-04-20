<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    $branch_id = $_POST['branch_id'];
    $destination = $_POST['destination'];

    try {
        $sql = "SELECT username FROM users WHERE username = :username AND user_id != :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if($stmt->rowCount() > 0){
            header("Location: ".$destination."?editstatus=exist");
            exit();
        }



        $sql = "";
        $hashedPassword = "";
        if (!empty($password)) {
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $sql = "UPDATE users SET username = :username, password = :password, branch_id = :branch_id WHERE user_id = :user_id";
        }
        else{
            $sql = "UPDATE users SET username = :username, branch_id = :branch_id WHERE user_id = :user_id";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':branch_id', $branch_id);
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

        header("Location: ".$destination."?editstatus=success");
        exit();

    } catch (PDOException $e) {
        header("Location: ".$destination."?editstatus=error");
        exit();
    }
}

?>