<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_name = $_POST['category_name'];

    try {
        $sql = "SELECT * FROM categories WHERE category_name = :category_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: categories.php?status=exist");
        exit();
        }
        else{
            $sql = "INSERT INTO categories (category_name) VALUES (:category_name)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->execute();

            header("Location: categories.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: categories.php?status=error");
        exit();
    }
}

?>