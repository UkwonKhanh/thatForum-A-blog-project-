<?php
ob_start();
require_once '../config/config_session.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}
try {
    require_once '../config/database.php';
    require_once '../config/function.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $errors = [];
    
    // get user details
    $user_account = get_user_details($user_id);

} catch (PDOException $e) {
    $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    die();
}

