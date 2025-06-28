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
    header("location: ../templates/addmodule.html.php");
    exit();
}else{
    try{
       // Insert new module into database
       $stmt = $pdo->prepare("INSERT INTO modules (module_code, module_name) VALUES 
       (:module_code, :module_name)");
       $stmt->bindParam(':module_code', $module_code, PDO::PARAM_STR);
       $stmt->bindParam(':module_name', $module_name, PDO::PARAM_STR);
       $stmt->execute();
        
        
        $success['succes_update'] = 'Add module successfully!';
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