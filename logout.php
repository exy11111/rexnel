<?php
require ('db.php');
require ('session.php');
if (isset($_SESSION['loggedin'])) {
    unset($_SESSION['loggedin']);
    unset($_SESSION['username']);
    session_destroy();
    header("Location: login.php");
    exit();
}
?>
