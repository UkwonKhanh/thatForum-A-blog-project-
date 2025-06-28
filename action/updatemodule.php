<?php
ob_start();
require_once "../config/config_session.php";
// Check if user is logged in, redirect if not
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}
try{
if($_SERVER['REQUEST_METHOD']==="POST"){	
require_once "../config/database.php";
require_once "../config/function.php";


$module_code = $_POST["moduleCode"];	
$module_name = $_POST['moduleName'];
$module_id = $_POST['module_id'];

// Validate user input
$success = [];
$errors = [];


$errors = [];
if (empty($module_code)){
    $errors["empty_module_code"] = "Please fill in module code";
}	
if (empty($module_name)){
    $errors["empty_module_name"] = "Please fill in module name";
}

if ($errors){
    $_SESSION['errors'] = $errors;
    header("location: ../templates/updatemodule.html.php");
    exit();
}else{
    try{
    // Update user account in database
        $stmt = $pdo->prepare("UPDATE modules 
            SET module_code = :module_code, 
            module_name = :module_name
            WHERE module_id = :module_id");
        $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
        $stmt->bindParam(':module_code', $module_code, PDO::PARAM_STR);
        $stmt->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        $stmt->execute();
        
        
        $success['succes_update'] = 'Update module successfully!';
        $_SESSION['success'] = $success;
        header('Location: ../templates/adminmodule.html.php');
        exit();
        }catch(PDOException $e){
            $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
            $_SESSION['errors'] = $errors;
            header('Location: ../templates/adminmodule.html.php');
            exit;
        }
    }}
}catch(Exception $e){
    $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    header('Location: ../templates/adminmodule.html.php');
    die();
}