<?php
require ('db.php');
require ('session.php');

$branch_id = $_GET['id'];

$sql = "SELECT branch_id, branch_name, location, opening_time, closing_time FROM branch WHERE branch_id = :branch_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':branch_id', $branch_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

if ($row) {
    echo json_encode([
        'branch_id' => $row['branch_id'],
        'branch_name' => $row['branch_name'],
        'location' => $row['location'],
        'opening_time' => $row['opening_time'],
        'closing_time' => $row['closing_time']
    ]);
} else {
    echo json_encode(['error' => 'Branch not found']);
}

$stmt->closeCursor();
?>
