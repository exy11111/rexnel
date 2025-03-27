<?php
    require('db.php');

    if (isset($_POST['barcode'])) {
        $barcode = $_POST['barcode'];

        $sql = "SELECT i.item_id, c.category_name, b.brand_name, s.supplier_name, ss.size_name, i.stock, i.price FROM items i
        JOIN categories c ON i.category_id = c.category_id
        JOIN brands b ON i.brand_id = b.brand_id
        JOIN suppliers s ON i.supplier_id = s.supplier_id 
        JOIN sizes ss ON i.size_id = ss.size_id
        WHERE barcode = :barcode";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':barcode', $barcode);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($item);
        } else {
            echo json_encode(['error' => 'Item not found.']);
        }
    }

    else if (isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];

        $sql = "SELECT i.barcode, c.category_name, b.brand_name, s.supplier_name, ss.size_name, i.stock, i.price FROM items i
            JOIN categories c ON i.category_id = c.category_id
            JOIN brands b ON i.brand_id = b.brand_id
            JOIN suppliers s ON i.supplier_id = s.supplier_id 
            JOIN sizes ss ON ss.size_id = i.size_id
            WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($item);
        } else {
            echo json_encode(['error' => 'Item not found.']);
        }
    }
?>