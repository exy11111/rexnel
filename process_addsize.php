<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $size_name = $_POST['size_name'];
    $size_description = $_POST['size_description'];
    $branch_id = $_SESSION['branch_id'];

    try {
        $sql = "SELECT * FROM sizes WHERE size_name = :size_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':size_name', $size_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: sizes.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO sizes (size_name, size_description, branch_id) VALUES (:size_name, :size_description, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':size_name', $size_name);
            $stmt->bindParam(':size_description', $size_description);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->execute();

            header("Location: sizes.php?status=success");
            exit();
        }
    }
    catch (PDOException $e) {
        header("Location: sizes.php?status=error");
        exit();
    }

}

?>