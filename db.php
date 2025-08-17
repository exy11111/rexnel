<?php
if ($_SERVER['HTTP_HOST'] === 'houseoflocal.store') {
    $servername = "auth-db457.hstgr.io";
    $username   = "u119634533_houseoflocal";
    $password   = "C^s9rtu#ZE";
    $dbname     = "u119634533_houseoflocal";
} else {
    $servername = "localhost";
    $username   = "root";
    $password   = "";
    $dbname     = "houseoflocal_db";
}

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname;charset=utf8", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
