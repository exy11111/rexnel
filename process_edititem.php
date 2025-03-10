<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];
    $item_id = $_POST['item_id'];

    try {
        $sql = "UPDATE items SET item_name = :item_name, category_id = :category_id, price = :price WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        header("Location: items.php?editstatus=success");
        exit();

    } catch (PDOException $e) {
        header("Location: items.php?editstatus=error");
        exit();
    }
}

?>