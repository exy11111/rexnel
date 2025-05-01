<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ftoken'], $_POST['new_password'], $_POST['confirm_password'])) {
    $ftoken = $_POST['ftoken'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if passwords match
    if ($new_password !== $confirm_password) {
        // Passwords don't match, redirect to reset page with an error message
        header('Location: reset_password.php?ftoken=' . urlencode($ftoken) . '&error=password_mismatch');
        exit;
    }

    // Hash the new password
    $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

    // Verify that the token exists in the database
    $stmt = $conn->prepare("SELECT user_id FROM users WHERE ftoken = :ftoken");
    $stmt->bindParam(':ftoken', $ftoken);
    $stmt->execute();

    if ($stmt->rowCount() === 1) {
        // Get the user ID
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_id = $row['user_id'];

        // Update the user's password and reset the ftoken
        $updateStmt = $conn->prepare("UPDATE users SET password = :password, ftoken = NULL WHERE user_id = :user_id");
        $updateStmt->execute([
            ':password' => $hashed_password,
            ':user_id' => $user_id
        ]);

        // Redirect to login page with a success message
        header('Location: login.php?reset=success');
        exit;
    } else {
        // Invalid token, redirect to login with an error
        header('Location: login.php?reset=error');
        exit;
    }
} else {
    // Invalid request, redirect to login page
    header('Location: login.php?reset=error');
    exit;
}
?>
