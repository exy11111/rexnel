<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $qty = $_POST['qty'];

    try{
        $sql = "UPDATE stock_requests SET status = 'Received' WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        $sql = "SELECT item_id FROM stock_requests WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $item_id = $stmt->fetchColumn();

        $sql = "UPDATE items 
        SET 
            stock = stock + :qty,
            stock_admin = stock_admin - :qty
        WHERE item_id = :item_id";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':qty', $qty, PDO::PARAM_INT);
        $stmt->bindParam(':item_id', $item_id, PDO::PARAM_INT);
        $stmt->execute();


        header("Location: request_stock.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: request_stock.php?editstatus=error");
        exit();
    }
    
}

?>