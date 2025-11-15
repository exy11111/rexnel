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
    $stmt = $conn->prepare("SELECT item_id, quantity FROM supplier_orders WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);
    $item = $stmt->fetch();

    $stmt = $conn->prepare("UPDATE items SET stock = stock + :q WHERE item_id = :item_id");
    $stmt->execute([':q' => $item['quantity'], ':item_id' => $item['item_id']]);

    $stmt = $conn->prepare("UPDATE supplier_orders SET status = 'Received' WHERE order_id = :order_id");
    $stmt->execute([':order_id' => $order_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found or already marked']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
