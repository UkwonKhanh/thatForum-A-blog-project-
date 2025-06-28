<?php
ob_start();
require_once '../config/database.php';
require_once '../config/config_session.php';
require_once '../config/function.php';
try {
    if (isset($_GET['question_id'])) {
        $question_id = $_GET['question_id'];
        
        $errors = [];
        $success = [];
        // Retrieve the image ID associated with the question
        $query = "SELECT image_id FROM questions WHERE question_id = :question_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result['image_id'] !== null) {
            $image_id = $result['image_id'];

            // Delete the question
            $query = "DELETE FROM questions WHERE question_id = :question_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':question_id', $question_id);
            $stmt->execute();

            // Delete the image record from the images table
            $query = "DELETE FROM images WHERE image_id = :image_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':image_id', $image_id);
            $stmt->execute();

            // Delete the image file
            $query = "SELECT image_path FROM images WHERE image_id = :image_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':image_id', $image_id);
            $stmt->execute();
            $result = $stmt->fetch();

            if ($result['image_path'] !== null) {
                $image_path = $result['image_path'];
                if (file_exists($image_path)) {
                    unlink($image_path);
                }
            }
        }
        // Delete the question
        $query = "DELETE FROM questions WHERE question_id = :question_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();

        // Check if there are any comments associated with the question
        $query = "SELECT COUNT(*) AS comment_count FROM comments WHERE question_id = :question_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':question_id', $question_id);
        $stmt->execute();
        $result = $stmt->fetch();

        if ($result['comment_count'] > 0) {
            // Delete the comments associated with the question
            $query = "DELETE FROM comments WHERE question_id = :question_id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':question_id', $question_id);
            $stmt->execute();
        }

        if($_SESSION['admin'] == true){
            $success['delete_success'] = 'Question delete successfully';
            $_SESSION['success'] = $success; 
            header('Location: ../templates/adminhome.html.php');
            exit;
        }else{
        // Redirect to the questions page
        $success['delete_success'] = 'Question delete successfully';
        $_SESSION['success'] = $success; 
        header('Location: ../templates/userquestion.html.php');
        exit;}
    } else {
        // Display an error message if failed to delete
        $errors['error_fail'] = "Error: Fail deleting the question.";
        $_SESSION['errors'] = $errors;
        header("location:../templates/userquestion.html.php");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}