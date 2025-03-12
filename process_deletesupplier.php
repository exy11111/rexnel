<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['supplier_id'])) {
        $supplier_id = $_POST['supplier_id'];
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
?>
