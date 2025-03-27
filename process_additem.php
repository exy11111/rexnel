<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barcode = $_POST['barcode'];
    $item_name = $_POST['item_name'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $supplier_id = $_POST['supplier_id'];
    $size_id = $_POST['size_id'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];

    try {
        $sql = "SELECT * FROM items WHERE barcode = :barcode OR (item_name = :item_name AND size_id = :size_id AND supplier_id = :supplier_id AND brand_id = :brand_id AND category_id = :category_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: stock.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO items (barcode, item_name, category_id, brand_id, supplier_id, size_id, price, stock) 
            VALUES (:barcode, :item_name, :category_id, :brand_id, :supplier_id, :size_id, :price, :stock)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->bindParam(':supplier_id', $supplier_id);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
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