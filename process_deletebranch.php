<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['branch_id'])) {
        $branch_id = $_POST['branch_id'];
        try {
                $sql1 = "DELETE FROM branch WHERE branch_id = :branch_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':branch_id', $branch_id);
                $stmt1->execute();
    
                echo 'success';
            
        } catch (PDOException $e) {
            echo 'error';
        }
    }
}
?>
