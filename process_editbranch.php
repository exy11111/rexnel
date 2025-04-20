<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $branch_id = $_POST['branch_id'];
    $branch_name = $_POST['branch_name'];
    $location = $_POST['location'];
    $opening_time = $_POST['opening_time'];
    $closing_time = $_POST['closing_time'];

    try {
        $sql = "SELECT branch_id FROM branch WHERE branch_name = :branch_name AND branch_id != :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':branch_name', $branch_name);
        $stmt->execute();
        
        if($stmt->rowCount() > 0){
            header("Location: branches.php?status=exist");
            exit();
        }

        $sql = "UPDATE branch SET branch_name = :branch_name, location = :location, opening_time = :opening_time, closing_time = :closing_time WHERE branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':branch_name', $branch_name);
        $stmt->bindParam(':location', $location);
        $stmt->bindParam(':opening_time', $opening_time);
        $stmt->bindParam(':closing_time', $closing_time);
        $stmt->execute();

        header("Location: branches.php?editstatus=success");
        exit();

    } catch (PDOException $e) {
        header("Location: branches.php?editstatus=error");
        exit();
    }
}

?>