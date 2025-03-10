<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $category_id = $_POST['category_id'];
    $price = $_POST['price'];

    try {
        $sql = "SELECT * FROM items WHERE item_name = :item_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: items.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO items (item_name, category_id, price) VALUES (:item_name, :category_id, :price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':price', $price);
            $stmt->execute();

            header("Location: items.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: items.php?status=error");
        exit();
    }

}

?>