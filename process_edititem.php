<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_name = $_POST['item_name'];
    $item_id = $_POST['item_id'];
    $category_id = $_POST['category_id'];
    $brand_id = $_POST['brand_id'];
    $supplier_id = $_POST['supplier_id'];

    try {
        $sql = "SELECT * FROM items WHERE item_name = :item_name AND category_id = :category_id AND brand_id = :brand_id AND supplier_id = :supplier_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0 && $row['item_id'] != $item_id) {
            header("Location: items.php?status=exist");
            exit();
        }
        else{
            $sql = "UPDATE items SET item_name = :item_name, category_id = :category_id, brand_id = :brand_id, supplier_id = :supplier_id WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->bindParam(':supplier_id', $supplier_id);
            
            $stmt->execute();
    
            header("Location: items.php?editstatus=success");
            exit();
        }
       

    } catch (PDOException $e) {
        header("Location: items.php?editstatus=error");
        exit();
    }
}

?>