<?php
header('Content-Type: application/json');

require ('session.php');
require ('db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $conn->prepare("SELECT status FROM supplier_orders WHERE order_id = :order_id LIMIT 1");
    $stmt->bindParam(':order_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        echo json_encode($item);
    } else {
        echo json_encode(['error' => 'Order not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
