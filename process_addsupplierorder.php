<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $date = date("Y-m-d");
    $status = 'Pending';
    

    try {
        $sql = "SELECT supplier_price FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        $supplier_price = $stmt->fetchColumn();
        $amount = $quantity * $supplier_price;

        $stmt = $conn->prepare("INSERT INTO supplier_orders (quantity, item_id, amount, date, status) VALUES (:quantity, :item_id, :amount, :date, :status)");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        $stmt = $conn->prepare("SELECT item_name FROM items WHERE item_id = :item_id");
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();
        $item_name = $stmt->fetchColumn();

        $added_by = $_SESSION['username'];
        $formatted_price = number_format($amount, 2, '.', '');
        $message = "$added_by placed an order: {$item_name}, {$quantity} pcs, ₱{$formatted_price}";
        $icon = "bi-plus-circle";
        $target_url = "orderssupplier.php";
        $timestamp = date('Y-m-d H:i:s');

        $sql = "SELECT user_id FROM users WHERE role_id = 3";
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

        header("Location: adminorderhistory.php?order=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: adminorderhistory.php?order=error");
        exit();
    }

}

?>