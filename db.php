<?php
$servername = "localhost";  
$username = "u829594790_rexnel_admin";         
$password = "S8uN4#qPm2bV!rYx";             
$dbname = "u829594790_houseoflocal";   

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
?>
