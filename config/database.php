<?php 
$dbname = "mysql:host=localhost;dbname=coursework_comp1841;charset=utf8mb4";
$dbusername = "root";
$dbpwd = "";

// temporary for website
// $dbname = "mysql:host=sql102.infinityfree.com;dbname=if0_39339193_coursework_comp1841;charset=utf8mb4";
// $dbusername = "if0_39339193";
// $dbpwd = "easyR3Member09";

try {
    $pdo = new PDO($dbname, $dbusername, $dbpwd);
    $pdo -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    die("Connection failed: " . $e -> getMessage());
}
