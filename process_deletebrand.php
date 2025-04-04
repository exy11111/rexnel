<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['brand_id'])) {
        $brand_id = $_POST['brand_id'];

        $sql = "SELECT * FROM items WHERE brand_id = :brand_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':brand_id', $brand_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'exist';
        }
        else{
            try {
            $sql1 = "DELETE FROM brands WHERE brand_id = :brand_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':brand_id', $brand_id);
            $stmt1->execute();
            echo 'success';
            
            } catch (PDOException $e) {
                echo 'error';
            }
        }
        
    }
}
?>
