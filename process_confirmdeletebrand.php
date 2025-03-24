<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['brand_id'])) {
        $brand_id = $_POST['brand_id'];

        try {
            $sql = "DELETE FROM items WHERE brand_id = :brand_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':brand_id', $brand_id);
            $stmt->execute();

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
?>
