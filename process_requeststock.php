<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $branch_id = $_SESSION['branch_id'];
    $user_id = $_SESSION['user_id'];
    $status = "Pending";
    $now = date("Y-m-d H:i:s");

    try {
        $sql = "INSERT INTO stock_requests (item_id, quantity, branch_id, user_id, status, created_at) 
        VALUES 
        (:item_id, :quantity, :branch_id, :user_id, :status, :created_at)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':created_at', $now);
        $stmt->execute();

        header("Location: request_sdtock.php?status=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: request_stock.php?status=error");
        exit();
    }

}

?>