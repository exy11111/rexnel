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
    $branch_id = $_POST['branch_id'];
    $is_discounted = isset($_POST['is_discounted']) ? (int)$_POST['is_discounted'] : 0;
    $discount_price = ($is_discounted === 1) ? $_POST['discount_price'] : null;

    try {
        $sql = "SELECT * FROM items WHERE (barcode = :barcode AND branch_id = :branch_id)
        OR 
        (item_name = :item_name AND size_id = :size_id AND supplier_id = :supplier_id AND brand_id = :brand_id AND category_id = :category_id AND branch_id = :branch_id)";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: stock.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO items (barcode, item_name, category_id, brand_id, supplier_id, size_id, price, stock, branch_id, is_discounted, discount_price) 
            VALUES (:barcode, :item_name, :category_id, :brand_id, :supplier_id, :size_id, :price, :stock, :branch_id, :is_discounted, :discount_price)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':barcode', $barcode);
            $stmt->bindParam(':item_name', $item_name);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->bindParam(':supplier_id', $supplier_id);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':stock', $stock);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->bindParam(':is_discounted', $is_discounted);
            if ($discount_price === null) {
                $stmt->bindValue(':discount_price', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':discount_price', $discount_price);
            }
            $stmt->execute();
            
            $added_by = $_SESSION['username'];
            $formatted_price = number_format($price, 2, '.', '');
            $message = "$added_by added an item: {$item_name} ({$stock} pcs, ₱{$formatted_price})";
            $icon = "bi-plus-circle";
            $target_url = "stock.php";
            $timestamp = date('Y-m-d H:i:s');

            $sql = "SELECT user_id FROM users WHERE branch_id = :branch_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':branch_id', $branch_id);
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

            header("Location: stock.php?b=".$branch_id."&status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: stock.php?b=".$branch_id."&status=error");
        exit();
    }

}

?>