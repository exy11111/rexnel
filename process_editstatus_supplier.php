<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $status = $_POST['status'];
    $order_id = $_POST['order_id'];

    try{
        $sql = "UPDATE supplier_orders SET status = :status WHERE order_id = :order_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':order_id', $order_id);
        $stmt->execute();

        header("Location: orderssupplier.php?status=success");
        exit();
        
    }
    catch (PDOException $e) {
        header("Location: orderssupplier.php?status=error");
        exit();
    }
    
}

?>