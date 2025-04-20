<?php
require ('db.php');
require ('session.php');

$user_id = $_GET['id'];

$sql = "SELECT user_id, username, branch_id FROM users WHERE user_id = :user_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt->execute();

$row = $stmt->fetch(PDO::FETCH_ASSOC);

$sql2 = "SELECT firstname, lastname, email FROM userdetails WHERE user_id = :user_id";
$stmt2 = $conn->prepare($sql2);
$stmt2->bindParam(':user_id', $user_id, PDO::PARAM_INT);
$stmt2->execute();

$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if ($row && $row2) {
    echo json_encode([
        'user_id' => $row['user_id'],
        'branch_id' => $row['branch_id'],
        'firstname' => $row2['firstname'],
        'lastname' => $row2['lastname'],
        'username' => $row['username'],
        'email' => $row2['email']
    ]);
} else {
    echo json_encode(['error' => 'User not found']);
}

$stmt->closeCursor();
?>
