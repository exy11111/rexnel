<?php
require ('db.php');
require ('session.php');

$brand_id = $_GET['id'];

$sql = "SELECT * FROM brands WHERE brand_id = :brand_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':brand_id', $brand_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'brand_id' => $row['brand_id'],
        'brand_name' => $row['brand_name'],
        'brand_description' => $row['brand_description']
    ]);
} else {
    echo json_encode(['error' => 'Size not found']);
}

$stmt->closeCursor();
?>
