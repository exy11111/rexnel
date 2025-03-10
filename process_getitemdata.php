<?php
require ('db.php');
require ('session.php');

$item_id = $_GET['id'];

$sql = "SELECT item_id, item_name, price, category_id FROM items WHERE item_id = :item_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'item_id' => $row['item_id'],
        'item_name' => $row['item_name'],
        'price' => $row['price'],
        'category_id' => $row['category_id']
    ]);
} else {
    echo json_encode(['error' => 'Item not found']);
}

$stmt->closeCursor();
?>
