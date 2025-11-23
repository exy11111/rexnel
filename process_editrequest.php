<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    try{
        $sql = "UPDATE stock_requests SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        header("Location: stock_requests.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: stockrequests.php?editstatus=error");
        exit();
    }
    
}

?>