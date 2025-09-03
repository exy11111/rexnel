<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $price = $_POST['price'];

    try{
        $sql = "UPDATE items SET supplier_price = :price WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':price', $price);
        $stmt->execute();

        header("Location: pricingsupplier.php?status=success");
        exit();
        
    }
    catch (PDOException $e) {
        header("Location: pricingsupplier.php?status=error");
        exit();
    }
    
}

?>