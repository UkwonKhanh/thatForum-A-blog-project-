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
        $question_title = $_POST['questionTitle'];
        $question_content = $_POST['questionContent'];
        $module_id = $_POST['module_id'];
        $user_id = $_SESSION['user_id'];
        
        // handle images upload
        $image = $_FILES['questionImage'];
        $image_id = null;
        $success = [];
        $errors = [];
    if (empty_title($question_title)){
        $errors["empty title"] = "Question title is required";
    }
    if (empty_content($question_content)){
        $errors["empty content"] = "Question content is required";
    }
    if (empty($module_id)){
        $errors["empty module"] = "Module is required";
    }
        if ($image['size'] > 0) {
            // Check the image type and size
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif', 'image/jpg');
            if (in_array($image['type'], $allowed_types) && $image['size'] <= 1024 * 1024) {
                // Upload the image to a directory
                $upload_dir = '../images/';
                $image_name = uniqid() . '_' . $image['name'];
                $image_path = $upload_dir . $image_name;

                if (move_uploaded_file($image['tmp_name'], $image_path)) {
                    // Insert the image into the images table
                    $stmt = $pdo->prepare("INSERT INTO images (image_path, image_alt, updloaded_at) VALUES (:image_path, :image_alt, NOW())");
                    $stmt->bindParam(":image_path", $image_path);
                    $stmt->bindParam(":image_alt", $image['name']);
                    $stmt->execute();
                    $image_id = $pdo->lastInsertId();
                } else {
                    // Handle the error, e.g., display an error message
                    $errors['errors_image'] = "Failed to upload image.";
                }
            } else {
                // Handle the error, e.g., display an error message
                $errors['errors_image'] = "Invalid image type or size.";
            }
        }
        if ($errors){
            $_SESSION['errors'] = $errors;
            header("location: ../templates/postquestion.html.php");
            exit();
        }else{
            // Prepare SQL statement
            $stmt = $pdo->prepare("INSERT INTO questions (title, content,user_id, module_id, image_id) VALUES (:title, :content, :user_id, :module_id, :image_id)");
        
            // Bind parameters and execute statement
            $stmt->bindParam(":user_id", $user_id); 
            $stmt->bindParam(":title", $question_title);
            $stmt->bindParam(":content", $question_content);
            $stmt->bindParam(":module_id", $module_id);
            $stmt->bindParam(":image_id", $image_id);
            $stmt->execute();

            // success announce
            $success['post success'] = 'Post question successfully'; 
            $_SESSION['success'] = $success;
            header("location: " . ($_SESSION['admin'] ? "../templates/adminhome.html.php" : "../templates/homepage.html.php"));

            exit();
        }

    }}catch (PDOException $e) {
    $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    header("location: ../templates/postquestion.html.php");
    die();
    
}