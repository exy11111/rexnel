<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];

    if($quantity < 1){
        header("Location: stock.php?stockstatus=less");
        exit();
    }

    try {
        $sql = "SELECT item_id, stock, item_name FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            $currentStock = $data['stock'];
            $newStock = $currentStock + $quantity;
            $itemName = $data['item_name'];

            $sql = "UPDATE items SET stock = :stock WHERE item_id = :item_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':item_id', $item_id);
            $stmt->bindParam(':stock', $newStock);
            $stmt->execute();

            //notif
            $added_by = $_SESSION['username'];
            $message = "<strong>{$added_by}</strong> updated stock for <strong>{$itemName}</strong>: <strong>{$quantity}</strong> added.";
            $icon = "bi-bag-check";
            $target_url = "stock.php";
            $sql = "SELECT user_id FROM users";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $insertNotif = $conn->prepare("INSERT INTO notifications (user_id, message, icon, target_url) VALUES (:user_id, :message, :icon, :target_url)");
            foreach ($users as $user) {
                $insertNotif->execute([
                    ':user_id' => $user['user_id'],
                    ':message' => $message,
                    ':icon' => $icon,
                    ':target_url' => $target_url
                ]);
            }

            header("Location: stock.php?stockstatus=success");
            exit();
        }
        else{
            
        }
    }
    catch (PDOException $e) {
        header("Location: stock.php?stockstatus=error");
        exit();
    }

}

?>