<?php
require ('db.php');
require ('session.php');

$supplier_id = $_GET['id'];

$sql = "SELECT * FROM suppliers WHERE supplier_id = :supplier_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':supplier_id', $supplier_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'supplier_id' => $row['supplier_id'],
        'supplier_name' => $row['supplier_name'],
        'contact_name' => $row['contact_name'],
        'email' => $row['email'],
        'phone' => $row['phone'],
        'address' => $row['address']
    ]);
} else {
    echo json_encode(['error' => 'Size not found']);
}

$stmt->closeCursor();
?>
