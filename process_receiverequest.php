<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $id = $_GET['id'];
    $qty = $_GET['qty'];

    try{
        $sql = "UPDATE stock_requests SET status = 'Received' WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $sql = "SELECT item_id FROM stock_requests WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $item_id = $stmt->fetchColumn();

        $sql = "UPDATE items 
        SET 
            stock = stock + :qty,
            stock_admin = stock_admin - :qty
        WHERE item_id = :item_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = "SELECT item_name FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        $item_name = $stmt->fetchColumn();

        $sql = "SELECT branch_name FROM branch WHERE branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();
        $branch_name = $stmt->fetchColumn();

        //received notif para kay super admin
        $received_by = $_SESSION['username'];
        $message = "[$branch_name] $received_by successfully received the order: {$item_name}, {$item_quantity} pcs, ₱{$amount}";
        $icon = "bi-plus-circle";
        $target_url = "stock_requests.php";
        $timestamp = date('Y-m-d H:i:s');

        $sql = "SELECT user_id FROM users WHERE role_id = 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($users as $user) {
            $sql = "INSERT INTO notifications (user_id, message, icon, target_url, created_at) 
                    VALUES (:user_id, :message, :icon, :target_url, :created_at)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user['user_id']);
            $stmt->bindParam(':message', $message);
            $stmt->bindParam(':icon', $icon);
            $stmt->bindParam(':target_url', $target_url);
            $stmt->bindParam(':created_at', $timestamp);
            $stmt->execute();
        }


        header("Location: request_stock.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: request_stock.php?editstatus=error");
        exit();
    }
    
}

?>