<?php
header('Content-Type: application/json');

require ('session.php');
require ('db.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo json_encode(['error' => 'Invalid ID']);
    exit;
}

$id = (int) $_GET['id'];

try {
    $stmt = $conn->prepare("
        SELECT 
            i.item_name, 
            c.category_name, 
            b.brand_name, 
            s.size_name
        FROM items i
        LEFT JOIN categories c ON i.category_id = c.category_id
        LEFT JOIN brands b ON i.brand_id = b.brand_id
        LEFT JOIN sizes s ON i.size_id = s.size_id
        WHERE i.item_id = :item_id
        LIMIT 1
    ");
    $stmt->bindParam(':item_id', $id, PDO::PARAM_INT);
    $stmt->execute();

    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($item) {
        echo json_encode($item);
    } else {
        echo json_encode(['error' => 'Item not found']);
    }

} catch (PDOException $e) {
    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);
}
