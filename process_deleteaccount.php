<?php
require('db.php');
require('session.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        try {
            if($user_id == $_SESSION['user_id']){
                echo 'cant';
                exit();
            }
            else{
                $sql1 = "DELETE FROM userdetails WHERE user_id = :user_id";
                $stmt1 = $conn->prepare($sql1);
                $stmt1->bindParam(':user_id', $user_id);
                $stmt1->execute();
                $sql2 = "DELETE FROM users WHERE user_id = :user_id";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bindParam(':user_id', $user_id);
                $stmt2->execute();
    
                echo 'success';
            }
            
        } catch (PDOException $e) {
            echo 'error';
        }
    }
}
?>
