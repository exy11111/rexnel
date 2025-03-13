<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $size_id = $_POST['size_id'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    try {
        $sql = "SELECT item_id, size_id FROM stock WHERE item_id = :item_id AND size_id = :size_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: stock.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO stock (item_id, size_id, quantity, price) VALUES (:item_id, :size_id, :quantity, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->bindParam(':quantity', $quantity);
            $stmt->bindParam(':price', $price);
            $stmt->execute();

            header("Location: stock.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: stock.php?status=error");
        exit();
    }

}

?>