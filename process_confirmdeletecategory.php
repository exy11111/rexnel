<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['category_id'])) {
        $category_id = $_POST['category_id'];

        try {
            $sql = "DELETE FROM items WHERE category_id = :category_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_id', $category_id);
            $stmt->execute();

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
?>
