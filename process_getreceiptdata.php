<?php
require 'db.php';
require 'session.php';

if (isset($_GET['item_id'])) {
    $item_id = $_GET['item_id'];

    $stmt = $conn->prepare("SELECT item_name, price, stock, size_id FROM items WHERE item_id = :item_id");
    $stmt->bindParam(":item_id", $item_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        $stmt = $conn->prepare("SELECT size_name FROM sizes WHERE size_id = :size_id");
        $stmt->bindParam(":size_id", $row['size_id']);
        $stmt->execute();
        $size = $stmt->fetch(PDO::FETCH_ASSOC);
        $row['size_name'] = $size['size_name'];
        echo json_encode($row);
    } else {
        echo json_encode(["error" => "Item not found."]);
    }
}
?>