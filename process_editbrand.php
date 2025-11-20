<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_name = $_POST['brand_name'];
    $brand_description = $_POST['brand_description'];
    $brand_id = $_POST['brand_id'];

    try{
        $sql = "SELECT * FROM brands WHERE brand_name = :brand_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':brand_name', $brand_name);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($stmt->rowCount() > 0 && $row['brand_id'] != $brand_id) {
            header("Location: brands.php?status=exist");
            exit();
        }
        else{
            $sql = "UPDATE brands SET brand_name = :brand_name, 
            brand_description = :brand_description
            WHERE brand_id = :brand_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':brand_name', $brand_name);
            $stmt->bindParam(':brand_description', $brand_description);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->execute();

            header("Location: brands.php?editstatus=success");
            exit();
        }
        
    }
    catch (PDOException $e) {
        header("Location: brands.php?editstatus=error");
        exit();
    }
    
}

?>