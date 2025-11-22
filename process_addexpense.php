<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expensetype_id = $_POST['expensetype_id'];
    $amount = $_POST['amount'];
    $comment = $_POST['comment'];
    $branch_id = $_SESSION['branch_id'];

    try {
        
        $sql = "INSERT INTO expenses (expensetype_id, amount, comment, branch_id) VALUES (:expensetype_id, :amount, :comment, :branch_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':expensetype_id', $expensetype_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':comment', $comment);
        $stmt->bindParam(':branch_id', $branch_id);
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