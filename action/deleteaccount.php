<?php
ob_start();
require_once '../config/database.php';
require_once '../config/config_session.php';
require_once '../config/function.php';

try {
    if (isset($_GET['user_id'])) {
        $user_id = $_GET['user_id'];
        $errors = [];
        $success = [];

        // Delete messages associated with the user
        $query = "DELETE FROM messages WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Delete images associated with the user's questions
        $query = "DELETE FROM questions WHERE image_id IN (SELECT image_id FROM images WHERE image_id IN (SELECT image_id FROM questions WHERE user_id = :user_id))";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

         
        // Delete the actual image files from the server
        $query = "SELECT image_path FROM images WHERE image_id IN (SELECT image_id FROM questions WHERE user_id = :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $image_paths = $stmt->fetchAll(PDO::FETCH_COLUMN);
 
        foreach ($image_paths as $image_path) {
            if (file_exists($image_path)) {
                unlink($image_path); // delete the image file from the server
            }
        }
        
        // Delete images associated with the user's questions
        $query = "DELETE FROM images WHERE image_id IN (SELECT image_id FROM questions WHERE user_id = :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

       

        // Delete comments associated with the user's questions
        $query = "DELETE FROM comments WHERE question_id IN (SELECT question_id FROM questions WHERE user_id = :user_id)";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Delete questions associated with the user
        $query = "DELETE FROM questions WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Finally, delete the user
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();

        // Redirect to the questions page
        $success['delete_success'] = 'User and associated data deleted successfully';
        $_SESSION['success'] = $success;
        session_unset();
        session_destroy();
        header('Location: ../action/index.php');
        exit;
    } else {
        // Display an error message if failed to delete
        $errors['error_fail'] = "Error: Fail deleting the user.";
        $_SESSION['errors'] = $errors;
        header("location:../templates/userquestion.html.php");
    }
} catch (PDOException $e) {
    die("Database error: " . $e->getMessage());
} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage());
}