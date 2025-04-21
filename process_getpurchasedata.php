<?php
    require "db.php";
    require "session.php";

    if (!isset($_POST['purchase_id'])) {
        echo json_encode(["success" => false, "error" => "Invalid request."]);
        exit;
    }

    $purchase_id = $_POST['purchase_id'];

    try {
        $stmt = $conn->prepare("SELECT price FROM purchases WHERE purchase_id = :purchase_id");
        $stmt->execute([':purchase_id' => $purchase_id]);
        $purchase = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$purchase) {
            echo json_encode(["success" => false, "error" => "Purchase not found."]);
            exit;
        }

        $stmt = $conn->prepare("
            SELECT pi.item_id, i.item_name, pi.quantity, s.size, pi.quantity * i.price AS price
            FROM purchase_items pi
            JOIN items i ON pi.item_id = i.item_id
            LEFT JOIN sizes s ON i.size_id = s.size_id
            WHERE pi.purchase_id = :purchase_id
        ");
        $stmt->execute([':purchase_id' => $purchase_id]);
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "total_price" => $purchase['price'], "items" => $items]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "error" => $e->getMessage()]);
    }
?>
