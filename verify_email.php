<?php
require('db.php'); // your PDO connection
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    try {
        // Find user by token
        $sql = "SELECT user_id FROM users WHERE vtoken = :token AND is_verified = 0 LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':token', $token);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Mark as verified
            $sql = "UPDATE users 
                    SET is_verified = 1, vtoken = NULL 
                    WHERE user_id = :user_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':user_id', $user['user_id']);
            $stmt->execute();

            $message = "✅ Your email has been successfully verified.";
        } else {
            $message = "❌ Invalid or expired verification link.";
        }
    } catch (PDOException $e) {
        $message = "Database error: " . $e->getMessage();
    }
} else {
    $message = "No verification token provided.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Email Verification</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex justify-content-center align-items-center vh-100 bg-light">
    <div class="card shadow p-4 text-center" style="max-width: 400px;">
        <h3>Email Verification</h3>
        <p><?php echo htmlspecialchars($message); ?></p>
        <a href="login.php" class="btn btn-primary mt-3">Go to Login</a>
    </div>
</body>
</html>
