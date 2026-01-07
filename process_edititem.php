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
    $branch_id = $_SESSION['branch_id'];
    $is_discounted  = isset($_POST['is_discounted']) ? (int)$_POST['is_discounted'] : 0;
    $discount_price = ($is_discounted === 1 && isset($_POST['discount_price']) && $_POST['discount_price'] !== '')
        ? $_POST['discount_price']
        : null;

    try {
        $sql = "SELECT item_id FROM items WHERE 
        branch_id = :branch_id AND 
        (
            barcode = :barcode OR 
            (
                item_name = :item_name AND 
                category_id = :category_id AND 
                brand_id = :brand_id AND 
                supplier_id = :supplier_id AND 
                size_id = :size_id
            )
        ) AND item_id != :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->bindParam(':item_name', $item_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0) {
            header("Location: stock.php?status=exist");
            exit();
        }
        else{
            $sql = "UPDATE items SET 
                barcode = :barcode,
                item_name = :item_name,
                category_id = :category_id,
                brand_id = :brand_id,
                supplier_id = :supplier_id,
                size_id = :size_id,
                stock = :stock,
                price = :price,
                is_discounted = :is_discounted,
                discount_price = :discount_price
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
            $stmt->bindParam(':is_discounted', $is_discounted);

            if ($discount_price === null) {
                $stmt->bindValue(':discount_price', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':discount_price', $discount_price);
            }
            
            $stmt->execute();

            if($stock <= 10){
                // Create the notification message for low stock
                $added_by = $_SESSION['username'];
                $message = "Low stock alert: {$item_name} (only {$stock} pcs left)";
                $icon = "bi-exclamation-circle";  // Icon for low stock alert
                $target_url = "stock.php";  // Redirect to stock management page
                $timestamp = date('Y-m-d H:i:s');  // Current timestamp

                // Fetch users to notify
                $sql = "SELECT user_id FROM users WHERE branch_id = :branch_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':branch_id', $_SESSION['branch_id']);
                $stmt->execute();
                $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

                // Insert notification for each user
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
                $sql = "INSERT INTO notifications (user_id, message, icon, target_url, created_at) 
                            VALUES (:user_id, :message, :icon, :target_url, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $_SESSION['user_id']);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':icon', $icon);
                $stmt->bindParam(':target_url', $target_url);
                $stmt->bindParam(':created_at', $timestamp);
                $stmt->execute();
            }
    
            header("Location: stock.php?editstatus=success");
            exit();
        }
       

    } catch (PDOException $e) {
        header("Location: stock.php?editstatus=error");
        exit();
    }
}

?>
