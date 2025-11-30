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

        $sql = "SELECT item_name FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        $item_name = $stmt->fetchColumn();

        //received notif para kay super admin
        $received_by = $_SESSION['username'];
        $message = "$received_by requested an order: {$item_name}, {$quantity} pcs";
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

        header("Location: request_stock.php?status=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: request_stock.php?status=error");
        exit();
    }

}

?>