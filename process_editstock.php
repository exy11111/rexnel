<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $item_id = $_POST['item_id'];
    $size_id = $_POST['size_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];
    $stock_id = $_POST['stock_id'];

    try{
        $sql = "UPDATE stock SET item_name = :item_name,
        item_id = :item_id, 
        size_id = :size_id, 
        quantity = :quantity, 
        price = :price
        WHERE stock_id = :stock_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock_id', $stock_id);
        $stmt->execute();

        header("Location: stock.php?editstatus=success");
        exit();
        
    }
    catch (PDOException $e) {
        header("Location: stock.php?editstatus=error");
        exit();
    }
    
}

?>