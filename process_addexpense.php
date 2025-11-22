<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expensetype_id = $_POST['expensetype_id'];
    $amount = $_POST['amount'];
    $comment = $_POST['comment'];
    $branch_id = $_SESSION['branch_id'];
    $timestamp = date('Y-m-d H:i:s');

    try {
        
        $sql = "INSERT INTO expenses (expensetype_id, amount, comment, branch_id, created_at) VALUES (:expensetype_id, :amount, :comment, :branch_id, :created_at)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':expensetype_id', $expensetype_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':created_at', $timestamp);
        $stmt->execute();

        header("Location: expenses.php?status=success");
        exit();

    }
    catch (PDOException $e) {
        header("Location: expenses.php?status=error");
        exit();
    }

}

?>