<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = $_POST['id'];
    $status = $_POST['status'];

    try{
        $sql = "UPDATE stock_requests SET status = :status WHERE id = :id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        if($status === 'Delivered'){
            //received notif para kay super admin
            $received_by = $_SESSION['username'];
            $message = "$received_by delivered request ID #".$id.".";
            $icon = "bi-plus-circle";
            $target_url = "request_stock.php";
            $timestamp = date('Y-m-d H:i:s');

            $sql = "SELECT user_id FROM users WHERE role_id = 2";
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            foreach ($users as $user) {
                $sql = "INSERT INTO notifications (user_id, message, icon, target_url, created_at) 
                        VALUES (:user_id, :message, :icon, :target_url, :created_at)";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':user_id', $user['user_id']);
                $stmt->bindParam(':message', $message);
                $stmt->bindParam(':icon', $icon);
                $stmt->bindParam(':target_url', $target_url);
                $stmt->bindParam(':created_at', $timestamp);
                $stmt->execute();
            }
        }

        header("Location: stock_requests.php?editstatus=success");
        exit();
    }
    catch (PDOException $e) {
        header("Location: stock_requests.php?editstatus=error");
        exit();
    }
    
}

?>