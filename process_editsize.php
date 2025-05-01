<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $size_name = $_POST['size_name'];
    $size_description = $_POST['size_description'];
    $size_id = $_POST['size_id'];
    $branch_id = $_SESSION['branch_id'];

    try{

        $sql = "SELECT * FROM sizes WHERE size_name = :size_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':size_name', $size_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0 && $row['size_id'] != $size_id) {
            header("Location: sizes.php?status=exist");
            exit();
        }
        else{
            $sql = "UPDATE sizes SET size_name = :size_name, size_description = :size_description WHERE size_id = :size_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':size_name', $size_name);
            $stmt->bindParam(':size_description', $size_description);
            $stmt->bindParam(':size_id', $size_id);
            $stmt->execute();

            header("Location: sizes.php?editstatus=success");
            exit();
        }
        
    }
    catch (PDOException $e) {
        header("Location: sizes.php?editstatus=error");
        exit();
    }
    
}

?>