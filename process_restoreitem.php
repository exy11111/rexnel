<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['item_id'])) {
        $item_id = $_POST['item_id'];

        $sql = "SELECT * FROM stock WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            echo 'exist';
        }
        else{
            try {
                $sql1 = "UPDATE items SET is_disabled = 0 WHERE item_id = :item_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':item_id', $item_id);
                $stmt1->execute();
                echo 'success';
                
            } catch (PDOException $e) {
                echo 'error';
            }
        }


        
    }
}
?>
