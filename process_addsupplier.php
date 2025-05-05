<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name'];
    $contact_name = $_POST['contact_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $branch_id = $_POST['branch_id'];

    try {
        $sql = "SELECT supplier_name FROM suppliers WHERE supplier_name = :supplier_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: suppliers.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO suppliers (supplier_name, contact_name, email, phone, address, branch_id) VALUES (:supplier_name, :contact_name, :email, :phone, :address, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':supplier_name', $supplier_name);
            $stmt->bindParam(':contact_name', $contact_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->execute();

            header("Location: suppliers.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: suppliers.php?status=error");
        exit();
    }

}

?>