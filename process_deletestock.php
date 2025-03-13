<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['stock_id'])) {
        $stock_id = $_POST['stock_id'];
        try {
            $sql1 = "DELETE FROM stock WHERE stock_id = :stock_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':stock_id', $stock_id);
            $stmt1->execute();
            echo 'success';
            
        } catch (PDOException $e) {
            echo 'error';
        }
    }
}
?>
