<?php
require('session.php');
require('db.php'); 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name'];
    $contact_name = $_POST['contact_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $supplier_id = $_POST['supplier_id'];
    $branch_id = $_SESSION['branch_id'];

    try{
        $sql = "SELECT * FROM suppliers WHERE supplier_name = :supplier_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            if($row['supplier_id'] != $supplier_id){
                header("Location: suppliers.php?status=exist");
                exit();
            }
            
        }
        else{
            $sql = "UPDATE suppliers SET supplier_name = :supplier_name, 
            contact_name = :contact_name, 
            email = :email, 
            phone = :phone, 
            address = :address
            WHERE supplier_id = :supplier_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':supplier_name', $supplier_name);
            $stmt->bindParam(':contact_name', $contact_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':supplier_id', $supplier_id);
            $stmt->execute();

            header("Location: suppliers.php?editstatus=success");
            exit();
        }
        
    }
    catch (PDOException $e) {
        header("Location: suppliers.php?editstatus=error");
        exit();
    }
    
}

?>