<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplier_name = $_POST['supplier_name'];
    $contact_name = $_POST['contact_name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        $sql = "SELECT supplier_name FROM suppliers WHERE supplier_name = :supplier_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':supplier_name', $supplier_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: suppliers.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO suppliers (supplier_name, contact_name, email, phone, address) VALUES (:supplier_name, :contact_name, :email, :phone, :address)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':supplier_name', $supplier_name);
            $stmt->bindParam(':contact_name', $contact_name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':phone', $phone);
            $stmt->bindParam(':address', $address);
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