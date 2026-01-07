<?php
require 'db.php';
require 'session.php';

if (isset($_GET['item_id'])) {

    $item_id = $_GET['item_id'];

    $sql = "
    SELECT 
        i.item_name,
        pi.quantity,
        s.size_name,

        /* Price per unit (discount-aware) */
        CASE
            WHEN pi.is_discounted = 1 
                AND pi.unit_price IS NOT NULL
            THEN pi.unit_price
            ELSE i.price
        END AS unit_price,

        /* Total per line */
        pi.quantity * 
        (
            CASE
                WHEN pi.is_discounted = 1 
                    AND pi.unit_price IS NOT NULL
                THEN pi.unit_price
                ELSE i.price
            END
        ) AS item_price,

        pi.is_discounted

    FROM purchase_items pi
    JOIN items i ON i.item_id = pi.item_id
    JOIN sizes s ON i.size_id = s.size_id
    WHERE pi.purchase_id = :purchase_id
    ";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':purchase_id', $purchase_id);
    $stmt->execute();

    if ($stmt->rowCount() > 0) {
        echo json_encode($stmt->fetch(PDO::FETCH_ASSOC));
    } else {
        echo json_encode(["error" => "Item not found."]);
    }
}
?>
