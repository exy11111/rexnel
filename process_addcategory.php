<?php
require('session.php');
require('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Validate input
    if (!isset($_POST['category_name']) || trim($_POST['category_name']) === '') {
        header("Location: categories.php?status=empty");
        exit();
    }

    if (!isset($_SESSION['branch_id'])) {
        header("Location: categories.php?status=session_error");
        exit();
    }

    $category_name = trim($_POST['category_name']);
    $branch_id = $_SESSION['branch_id'];

    try {
        // Check if category already exists for this branch
        $sql = "SELECT * FROM categories WHERE category_name = :category_name AND branch_id = :branch_id";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':category_name', $category_name);
        $stmt->bindParam(':branch_id', $branch_id);
        $stmt->execute();

        if ($stmt->rowCount() > 0) {
            header("Location: categories.php?status=exist");
            exit();
        } else {
            // Insert new category
            $sql = "INSERT INTO categories (category_name, branch_id) VALUES (:category_name, :branch_id)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':category_name', $category_name);
            $stmt->bindParam(':branch_id', $branch_id);
            $stmt->execute();

            header("Location: categories.php?status=success");
            exit();
        }
    } catch (PDOException $e) {
        // Log the error
        error_log("Database Error: " . $e->getMessage());
        
        // Optional: display error for debugging (disable in production)
        echo "<h4>Database Error:</h4><pre>" . $e->getMessage() . "</pre>";
        // Redirect if preferred
        // header("Location: categories.php?status=error");
        exit();
    }
}
?>
