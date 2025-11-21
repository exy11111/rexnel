<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stock = $_POST['stock'];
    $item_id = $_POST['item_id'];

    try {

            $sql = "UPDATE items SET stock_admin = :stock
             WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':stock', $stock);
            $stmt->execute();
    
            header("Location: stock_admin.php?editstatus=success");
            exit();
       

    } catch (PDOException $e) {
        header("Location: stock_admin.php?editstatus=error");
        exit();
    }
}

?>
