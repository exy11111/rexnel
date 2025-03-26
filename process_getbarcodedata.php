<?php
    require('db.php');

    if (isset($_POST['barcode'])) {
        $barcode = $_POST['barcode'];

        $sql = "SELECT i.item_id, c.category_name, b.brand_name, s.supplier_name FROM items i
        JOIN categories c ON i.category_id = c.category_id
        JOIN brands b ON i.brand_id = b.brand_id
        JOIN suppliers s ON i.supplier_id = s.supplier_id 
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

        $sql = "SELECT i.barcode, c.category_name, b.brand_name, s.supplier_name FROM items i
            JOIN categories c ON i.category_id = c.category_id
            JOIN brands b ON i.brand_id = b.brand_id
            JOIN suppliers s ON i.supplier_id = s.supplier_id 
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

    else if (isset($_GET['size_id']) && isset($_GET['item_id'])) {
        $item_id = $_GET['item_id'];
        $size_id = $_GET['size_id'];

        $sql = "SELECT price, quantity FROM stock WHERE item_id = :item_id AND size_id = :size_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($item);
        } else {
            echo json_encode(['error' => 'Item not found.']);
        }
    }

    else if (isset($_GET['qsize']) && isset($_GET['qitem'])) {
        $item_id = $_GET['qitem'];
        $size_id = $_GET['qsize'];

        $sql = "SELECT price FROM stock WHERE item_id = :item_id AND size_id = :size_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->bindParam(':size_id', $size_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            $item = $stmt->fetch(PDO::FETCH_ASSOC);
            echo json_encode($item);
        } else {
            echo json_encode(['error' => 'Item not found.']);
        }
    }
?>