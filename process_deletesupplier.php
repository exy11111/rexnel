<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];

        $sql = "SELECT * FROM items WHERE supplier_id = :supplier_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->execute();
        if ($stmt->rowCount() > 0) {
            echo 'exist';
        }
        else{
            try {
                $sql1 = "DELETE FROM suppliers WHERE supplier_id = :supplier_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':supplier_id', $supplier_id);
                $stmt1->execute();
                echo 'success';
                
            } catch (PDOException $e) {
                echo 'error';
            }
        }

        
    }
}
?>
