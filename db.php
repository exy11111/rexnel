<?php
if ($_SERVER['HTTP_HOST'] === 'houseoflocal.store') {
    $servername = "localhost";
    $username   = "u119634533_houseoflocal";
    $password   = "3;C9MX9jvq";
    $dbname     = "u119634533_houseoflocal";
} else {
    $servername = "localhost";
    $username   = "root";
    $password   = "logic123";
    $dbname     = "houseoflocal_db";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
