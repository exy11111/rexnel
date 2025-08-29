<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    $date = date("Y-m-d");
    $status = 'Pending';
    

    try {
        $sql = "SELECT supplier_price FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        $supplier_price = $stmt->fetchColumn();
        $amount = $quantity * $supplier_price;

        $stmt = $conn->prepare("INSERT INTO supplier_orders (quantity, item_id, amount, date, status) VALUES (:quantity, :item_id, :amount, :date, :status)");
        $stmt->bindParam(':quantity', $quantity);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':status', $status);
        $stmt->execute();

        header("Location: stock.php?status=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: stock.php?status=error");
        exit();
    }

}

?>