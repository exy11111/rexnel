<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $size_name = $_POST['size_name'];
    $size_description = $_POST['size_description'];

    try {
        $sql = "SELECT * FROM sizes WHERE size_name = :size_name";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':size_name', $size_name);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: sizes.php?status=exist");
            exit();
        }
        else{
            $sql = "INSERT INTO sizes (size_name, size_description) VALUES (:size_name, :size_description)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':size_name', $size_name);
            $stmt->bindParam(':size_description', $size_description);
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