<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $barcode = $_POST['barcode'];
    $item_name = $_POST['item_name'];
    $item_id = $_POST['item_id'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $supplier_id = $_POST['supplier_id'];
    $size_id = $_POST['size_id'];
    $stock = $_POST['stock'];
    $price = $_POST['price'];

    try {
        $sql = "SELECT item_id FROM items WHERE barcode = :barcode OR (item_name = :item_name AND category_id = :category_id AND brand_id = :brand_id AND supplier_id = :supplier_id AND size_id = :size_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            header("Location: stock.php?status=exist");
            exit();
        }
        else{
            $sql = "UPDATE items SET barcode = :barcode, item_name = :item_name, category_id = :category_id, brand_id = :brand_id, supplier_id = :supplier_id, size_id = :size_id, stock = :stock, price = :price 
             WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->bindParam(':supplier_id', $supplier_id);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':price', $price);
            
            $stmt->execute();
    
            header("Location: stock.php?editstatus=success");
            exit();
        }
       

    } catch (PDOException $e) {
        header("Location: stock.php?editstatus=error");
        exit();
    }
}

?>