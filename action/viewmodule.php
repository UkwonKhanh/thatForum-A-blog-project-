<?php
ob_start();
require_once '../config/config_session.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();}
try {
    require_once '../config/database.php';
    // require_once "../templates/header.php";
    $user_id = $_SESSION['user_id'];
    $username = $_SESSION['username'];
    $errors = [];
    
    // retrieve modules data
    $sql_module= "SELECT * FROM modules";
    $stmt = $pdo -> prepare($sql_module);
    $stmt -> execute();
    $modules = $stmt -> fetchAll(PDO::FETCH_ASSOC);
    // die()
    
}catch(PDOException $e){
    $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    die();
}
