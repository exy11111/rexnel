<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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

    try {
        $sql = "SELECT * FROM suppliers WHERE supplier_name = :supplier_name AND branch_id = :branch_id AND supplier_id != :supplier_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->bindParam(':supplier_id', $supplier_id);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($row) {
            // If the row exists, redirect with a message indicating the supplier name already exists
            header("Location: suppliers.php?status=exist");
            exit();
        }

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
    } catch (PDOException $e) {
        // Output the error message for debugging
        echo "Error: " . $e->getMessage();
        exit();
    }
}
?>
