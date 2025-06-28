<?php
ob_start();
require_once "../config/config_session.php";
require_once "../config/function.php";
// Check if user is logged in, redirect if not
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}

try{
if ($_SERVER['REQUEST_METHOD']=="POST"){
require_once "../config/database.php";

// Validate user input
$success = [];
$errors = [];
// $user_id = $_SESSION["user_id"];
if (empty($_POST['questionTitle'])) {
    $errors['empty title'] = "Please enter a question title.";
}

if (empty($_POST['questionContent'])) {
    $errors['empty content'] = "Please enter question content.";
}

// Check if module is selected
if (empty($_POST['module_id'])) {
    $errors['empty module'] = "Please select a module.";
}



// If no errors proceed with update
if (empty($errors)) {
    $question_id = $_POST['question_id'];
    $question = get_question_details_by_id($question_id);
    $question_title = htmlspecialchars($_POST['questionTitle']);
    $question_content = htmlspecialchars($_POST['questionContent']);
    $module_id = $_POST['module_id'];
    $image_id = isset($question['image_id']) ? $question['image_id'] : null;

    // Image handling
    if (isset($_FILES['questionImage']) && $_FILES['questionImage']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['questionImage'];
        $image_path = null;
        $image_alt = null;
    
        if ($image['size'] !== 0) {
            // Check the file type using finfo
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mimeType = finfo_file($finfo, $image['tmp_name']);
            finfo_close($finfo);
    
            $allowed_types = array('image/jpeg', 'image/png', 'image/gif');
            if (in_array($mimeType, $allowed_types)) {
                // Check if the file is a valid image
                $imageInfo = getimagesize($image['tmp_name']);
                if ($imageInfo !== false) {
                    // Upload the image to a directory
                    $upload_dir = '../images/';
                    $image_name = uniqid() . '_' . $image['name'];
                    $image_path = $upload_dir . $image_name;
    
                    // Move the uploaded file to the uploads directory
                    if (move_uploaded_file($image['tmp_name'], $image_path)) {
                        $stmt = $pdo->prepare("INSERT INTO images (image_path, image_alt, updloaded_at) VALUES (:image_path, :image_alt, NOW())");
                        $stmt->bindParam(":image_path", $image_path);
                        $stmt->bindParam(":image_alt", $image['name']);
                        $stmt->execute();
                        $new_image_id = $pdo->lastInsertId();

                        // Update question with new image_id
                        $stmt = $pdo->prepare("UPDATE questions SET image_id = :image_id WHERE question_id = :question_id");
                        $stmt->bindParam(":image_id", $new_image_id);
                        $stmt->bindParam(":question_id", $question_id);
                        $stmt->execute();
                    } else {
                        $errors['errors_image'] = "Failed to upload image.";
                    }
                } else {
                    $errors['errors_image'] = "Invalid image file.";
                }
            } else {
                $errors['errors_image'] = "Only image files are allowed.";
            }
        }
    }
    // Update question in database
    try {
        $stmt = $pdo->prepare("UPDATE questions 
            SET title = :question_title, 
            content = :question_content,
            module_id = :module_id, 
            updated_at = NOW()
            WHERE question_id = :question_id");    // user_id = :user_id,
        $stmt->bindParam(':question_title', $question_title, PDO::PARAM_STR);
        $stmt->bindParam(':question_content', $question_content, PDO::PARAM_STR);
        // $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        $success['succes_update'] = 'Update question successfully!';
        $_SESSION['success'] = $success;
        
        if (!$_SESSION['admin']){
        header('Location: ../templates/userquestion.html.php');
        exit();}else{
            header('Location: ../templates/adminhome.html.php');
            exit;
        }
    } catch (PDOException $e) {
        $errors['errors_update'] = "Error updating question: " . $e->getMessage();
        $_SESSION['errors'] = $errors;
        header("location: ../templates/updatequestion.html.php");
        die();
        // error_log($e->getMessage());
    }
    }else{
        $_SESSION['errors'] = $errors;
        header("location: ../templates/updatequestion.html.php");
        die();
    }
        
    }
}catch(Exception $e){
    $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
    header("location: ../templates/updatequestion.html.php"); 
    $_SESSION['errors'] = $errors;
    die();}