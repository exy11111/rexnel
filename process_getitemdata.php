<?php
require ('db.php');
require ('session.php');

$item_id = $_GET['id'];

$sql = "SELECT barcode, item_id, item_name, category_id, brand_id, supplier_id, size_id, price, stock, stock_admin FROM items WHERE item_id = :item_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'barcode' => $row['barcode'],
        'item_id' => $row['item_id'],
        'item_name' => $row['item_name'],
        'category_id' => $row['category_id'],
        'brand_id' => $row['brand_id'],
        'supplier_id' => $row['supplier_id'],
        'size_id' => $row['size_id'],
        'price' => $row['price'],
        'stock' => $row['stock'],
        'stock_admin' => $row['stock_admin']
    ]);
} else {
    echo json_encode(['error' => 'Item not found']);
}

$stmt->closeCursor();
?>
