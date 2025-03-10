<?php
require ('db.php');
require ('session.php');

$size_id = $_GET['id'];

$sql = "SELECT size_id, size_name, size_description FROM sizes WHERE size_id = :size_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':size_id', $size_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'size_id' => $row['size_id'],
        'size_name' => $row['size_name'],
        'size_description' => $row['size_description']
    ]);
} else {
    echo json_encode(['error' => 'Size not found']);
}

$stmt->closeCursor();
?>
