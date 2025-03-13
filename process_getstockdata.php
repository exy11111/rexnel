<?php
require ('db.php');
require ('session.php');

$stock_id = $_GET['id'];

$sql = "SELECT * FROM stock WHERE stock_id = :stock_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':stock_id', $stock_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'stock_id' => $row['stock_id'],
        'item_id' => $row['item_id'],
        'size_id' => $row['size_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price'],
    ]);
} else {
    echo json_encode(['error' => 'Stock not found']);
}

$stmt->closeCursor();
?>
