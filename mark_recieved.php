<?php
header('Content-Type: application/json');

if ($_SERVER['HTTP_HOST'] === 'houseoflocal.store') {
    $servername = "localhost";
    $username   = "u119634533_houseoflocal";
    $password   = "3;C9MX9jvq";
    $dbname     = "u119634533_houseoflocal";
} else {
    $servername = "localhost";
    $username   = "root";
    $password   = "logic123";
    $dbname     = "houseoflocal_db";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Connection failed: ' . $e->getMessage()]);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

if (!isset($data['order_id'])) {
    echo json_encode(['success' => false, 'message' => 'Order ID missing']);
    exit;
}

$order_id = $data['order_id'];

try {
    $stmt = $conn->prepare("UPDATE orders SET status = 'Received' WHERE order_id = :order_id");
    $stmt->execute(['order_id' => $order_id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Order not found or already marked']);
    }
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
