<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_name = $_POST['category_name'];
    $category_id = $_POST['category_id'];

    try{
        $sql = "UPDATE categories SET category_name = :category_name WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        header("Location: categories.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: categories.php?editstatus=error");
        exit();
    }
    
}

?>