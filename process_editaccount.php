<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $user_id = $_POST['user_id'];
    $destination = $_POST['destination'];
    $branch_id = $_POST['branch_id'];

    try {
        $sql = "SELECT username FROM users WHERE username = :username AND user_id != :user_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: " . $destination . "?editstatus=exist");
            exit();
        }

        $sql = "UPDATE users SET username = :username";
        $params = [':username' => $username, ':user_id' => $user_id];

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

        $sql2 = "UPDATE userdetails SET firstname = :firstname, lastname = :lastname WHERE user_id = :user_id";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->bindParam(':firstname', $firstname);
        $stmt2->bindParam(':lastname', $lastname);
        $stmt2->bindParam(':user_id', $user_id);
        $stmt2->execute();

        header("Location: " . $destination . "?editstatus=success");
        exit();

    } catch (PDOException $e) {
        header("Location: " . $destination . "?editstatus=error");
        exit();
    }
}
?>
