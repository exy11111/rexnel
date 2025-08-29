<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $item_id = $_POST['item_id'];
    $quantity = $_POST['quantity'];
    

    try {
        $sql = "SELECT supplier_price FROM items WHERE item_id = :item_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':item_id', $item_id);
        $stmt->execute();

        $supplier_price = $stmt->fetchColumn();
        $amount = $quantity * $supplier_price;

        $stmt = $conn->prepare("");


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