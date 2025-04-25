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
            $sql = "INSERT INTO items (barcode, item_name, category_id, brand_id, supplier_id, size_id, price, stock, branch_id) 
            VALUES (:barcode, :item_name, :category_id, :brand_id, :supplier_id, :size_id, :price, :stock, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->bindParam(':supplier_id', $supplier_id);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':branch_id', $_SESSION['branch_id']);
            $stmt->execute();
            
            $added_by = $_SESSION['username'];
            $formatted_price = number_format($price, 2, '.', '');
            $message = "$added_by added an item: {$item_name} ({$stock} pcs, ₱{$formatted_price})";
            $icon = "bi-plus-circle";
            $target_url = "stock.php";
            $timestamp = date('Y-m-d H:i:s');

            $sql = "SELECT user_id FROM users WHERE branch_id = :branch_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':branch_id', $_SESSION['branch_id']);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

            
            foreach ($users as $user) {
                $sql = "INSERT INTO notifications (user_id, message, icon, target_url, created_at) 
                        VALUES (:user_id, :message, :icon, :target_url, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user['user_id']);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':icon', $icon);
                $stmt->bindParam(':target_url', $target_url);
                $stmt->bindParam(':created_at', $timestamp);
                $stmt->execute();
            }

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