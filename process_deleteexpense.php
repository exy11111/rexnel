<?php
    require('db.php');
    require('session.php');

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_POST['expense_id'])) {
            $expense_id = $_POST['expense_id'];

            try {
                $sql = "DELETE FROM expenses WHERE expense_id = :expense_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':expense_id', $expense_id);
                $stmt->execute();

                echo 'success';
                
            } catch (PDOException $e) {
                echo 'error';
            }
        }
    }
?>
