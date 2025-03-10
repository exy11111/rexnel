<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category_id'])) {
        $category_id = $_POST['category_id'];

        $sql = "SELECT * FROM items WHERE category_id = :category_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_id', $category_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'exist';
        }
        else{
            try {
                $sql1 = "DELETE FROM categories WHERE category_id = :category_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':category_id', $category_id);
                $stmt1->execute();
                echo 'success';
                
            } catch (PDOException $e) {
                echo 'error';
            }
        }

        
    }
}
?>
