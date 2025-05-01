<?php
require('session.php');
require('db.php');


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $brand_name = $_POST['brand_name'];
    $brand_description = $_POST['brand_description'];
    $branch_id = $_SESSION['branch_id'];

    try {
        $sql = "SELECT brand_name FROM brands WHERE brand_name = :brand_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':brand_name', $brand_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: brands.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO brands (brand_name, brand_description, branch_id) VALUES (:brand_name, :brand_description, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':brand_name', $brand_name);
            $stmt->bindParam(':brand_description', $brand_description);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->execute();

            header("Location: brands.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: brands.php?status=error");
        exit();
    }

}

?>