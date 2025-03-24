<?php
    require('db.php');
    require('session.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['size_id'])) {
            $size_id = $_POST['size_id'];

            try {
                $sql = "DELETE FROM stock WHERE size_id = :size_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':size_id', $size_id);
                $stmt->execute();

                $sql1 = "DELETE FROM sizes WHERE size_id = :size_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':size_id', $size_id);
                $stmt1->execute();
                echo 'success';
                
            } catch (PDOException $e) {
                echo 'error';
            }
        }
    }
?>
