<?php
require('db.php');
require('session.php');

$branch_id = $_SESSION['branch_id'];

if (isset($_POST['barcode'])) {

    $barcode = $_POST['barcode'];

    $sql = "
    SELECT 
        i.item_id,
        c.category_name,
        b.brand_name,
        s.supplier_name,
        ss.size_name,
        i.stock,

        /* Effective price */
        CASE 
            WHEN i.is_discounted = 1 
                 AND i.discount_price IS NOT NULL
            THEN i.discount_price
            ELSE i.price
        END AS price,

        i.is_discounted,
        i.discount_price

    FROM items i
    JOIN categories c ON i.category_id = c.category_id
    JOIN brands b ON i.brand_id = b.brand_id
    JOIN suppliers s ON i.supplier_id = s.supplier_id 
    JOIN sizes ss ON i.size_id = ss.size_id
    WHERE i.barcode = :barcode
      AND i.branch_id = :branch_id
      AND i.is_disabled = 0
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':barcode', $barcode);
    $stmt->bindParam(':branch_id', $branch_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo json_encode(['error' => 'Item not found.']);
    }

} else if (isset($_POST['item_id'])) {

    $item_id = $_POST['item_id'];

    $sql = "
    SELECT 
        i.barcode,
        c.category_name,
        b.brand_name,
        s.supplier_name,
        ss.size_name,
        i.stock,

        /* Effective price */
        CASE 
            WHEN i.is_discounted = 1 
                 AND i.discount_price IS NOT NULL
            THEN i.discount_price
            ELSE i.price
        END AS price,

        i.is_discounted,
        i.discount_price

    FROM items i
    JOIN categories c ON i.category_id = c.category_id
    JOIN brands b ON i.brand_id = b.brand_id
    JOIN suppliers s ON i.supplier_id = s.supplier_id 
    JOIN sizes ss ON ss.size_id = i.size_id
    WHERE i.item_id = :item_id
      AND i.is_disabled = 0
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo json_encode(['error' => 'Item not found.']);
    }
}
?>
