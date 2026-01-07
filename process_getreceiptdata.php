<?php
require 'db.php';
require 'session.php';

if (isset($_GET['item_id'])) {

    $item_id = $_GET['item_id'];

    $sql = "
    SELECT 
        i.item_name,
        i.stock,
        s.size_name,

        /* Current selling price */
        CASE
            WHEN i.is_discounted = 1 
                 AND i.discount_price IS NOT NULL
            THEN i.discount_price
            ELSE i.price
        END AS price,

        i.is_discounted

    FROM items i
    JOIN sizes s ON i.size_id = s.size_id
    WHERE i.item_id = :item_id
      AND i.is_disabled = 0
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo json_encode(["error" => "Item not found."]);
    }
}
?>