<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $expense_id = $_POST['expense_id'];
    $expensetype_id = $_POST['expensetype_id'];
    $amount = $_POST['amount'];
    $comment = $_POST['comment'];

    try{
        $sql = "UPDATE expenses SET expensetype_id = :expensetype_id, amount = :amount, comment = :comment WHERE expense_id = :expense_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':expense_id', $expense_id);
        $stmt->bindParam(':expensetype_id', $expensetype_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':comment', $comment);
        $stmt->execute();

        header("Location: expenses.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: expenses.php?editstatus=error");
        exit();
    }
    
}

?>