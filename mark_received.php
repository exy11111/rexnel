<?php
header('Content-Type: application/json');
require('session.php');
require('db.php');

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID missing']);
    exit;
}

$order_id = $data['order_id'];

try {
    $stmt = $conn->prepare("SELECT 
        so.item_id, so.quantity, so.amount, i.item_name 
        FROM supplier_orders so
        LEFT JOIN items i ON i.item_id = so.item_id
        WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);
    $item = $stmt->fetch();
    $item_name = $item['item_name'];
    $item_quantity = $item['quantity'];
    $amount = number_format($item['amount'], 2);

    $stmt = $conn->prepare("UPDATE items SET stock_admin = stock_admin + :q WHERE item_id = :item_id");
    $stmt->execute([':q' => $item['quantity'], ':item_id' => $item['item_id']]);

    $stmt = $conn->prepare("UPDATE supplier_orders SET status = 'Received' WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);


    //received notif para kay super admin
    $received_by = $_SESSION['username'];
    $message = "$received_by successfully received the order: {$item_name}, {$item_quantity} pcs, ₱{$amount}";
    $icon = "bi-plus-circle";
    $target_url = "adminorderhistory.php";
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

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found or already marked']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
