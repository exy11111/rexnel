<?php
require ('db.php');
require ('session.php');

$category_id = $_GET['id'];

$sql = "SELECT category_id, category_name FROM categories WHERE category_id = :category_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);

if($row){
    echo json_encode([
        'category_id' => $row['category_id'],
        'category_name' => $row['category_name']
    ]);
}
else {
    echo json_encode(['error' => 'Category not found']);
}

$stmt->closeCursor();
?>