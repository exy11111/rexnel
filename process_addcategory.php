<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $category_name = $_POST['category_name'];
    $branch_id = $_SESSION['branch_id'];

    try {
        $sql = "SELECT * FROM categories WHERE category_name = :category_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: categories.php?status=exist");
        exit();
        }
        else{
            $sql = "INSERT INTO categories (category_name, branch_id) VALUES (:category_name, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':branch_id', $branch_id);
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