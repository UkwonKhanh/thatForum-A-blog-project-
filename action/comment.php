<?php
ob_start();
require_once '../config/config_session.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: index.php');
    exit();
}

try {
    require_once '../config/database.php';
    require_once "../config/function.php";

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Retrieve form data
        $question_id = $_POST['question_id'];
        $comments = $_POST['comment'];
        $user_id = $_SESSION['user_id'];
        
        $success = [];
        $errors = [];
    if (empty($comments)){
        $errors["empty_comment"] = "Please fill in comment";
    }
    if ($errors){
            $_SESSION['errors'] = $errors;
            header("location: ../templates/adminhome.html.php");
            exit();
        }else{
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO comments (comment, user_id, question_id) 
            VALUES (:comment, :user_id, :question_id)");
        
            // Bind parameters and execute statement
            $stmt->bindParam(":comment", $comments); 
            $stmt->bindParam(":user_id", $user_id);
            $stmt->bindParam(":question_id", $question_id);
            $stmt->execute();

            if ($_SESSION['admin']){
            // success announce
            $success['comment_success'] = 'Comment successfully'; 
            $_SESSION['success'] = $success;
            header("location: ../templates/adminhome.html.php");
            exit();
        }else{
            // success announce
            $success['comment_success'] = 'Comment successfully'; 
            $_SESSION['success'] = $success;
            header("location: ../templates/homepage.html.php");
            exit();
        }
        }

    }}catch (PDOException $e) {
    $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    header("location: ../templates/adminhome.html.php");
    die();
    
}