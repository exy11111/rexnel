<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];
        try {
            $sql1 = "DELETE FROM items WHERE item_id = :item_id";
            $stmt1 = $conn->prepare($sql1);
            $stmt1->bindParam(':item_id', $item_id);
            $stmt1->execute();
            echo 'success';
            
        } catch (PDOException $e) {
            echo 'error';
        }
    }
}
?>
