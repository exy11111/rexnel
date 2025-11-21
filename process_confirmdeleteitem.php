<?php
    require('db.php');
    require('session.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['item_id'])) {
            $brand_id = $_POST['item_id'];

            try {
                $sql = "DELETE FROM stock WHERE item_id = :item_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':item_id', $item_id);
                $stmt->execute();

                $sql1 = "UPDATE items SET is_disabled = 1 WHERE item_id = :item_id";
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
