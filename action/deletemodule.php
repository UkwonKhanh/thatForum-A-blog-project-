<?php
ob_start();
require_once "../config/config_session.php";
// Check if user is logged in, redirect if not
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}

try {
    if ($_SERVER['REQUEST_METHOD'] === "GET") {
        require_once "../config/database.php";
        require_once "../config/function.php";

        $module_id = $_GET["module_id"];

        // Validate user input
        $success = [];
        $errors = [];

        if (empty($module_id)) {
            $errors["empty_module_id"] = "Module ID is required";
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("location: ../templates/adminmodule.html.php");
            exit();
        } else {
            try {
                // Get all question IDs associated with the module
                $stmt = $pdo->prepare("SELECT question_id FROM questions WHERE module_id = :module_id");
                $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
                $stmt->execute();
                $questions = $stmt->fetchAll();

                foreach ($questions as $question) {
                    $question_id = $question['question_id'];

                    // Check if there are any comments associated with the question
                    $stmt = $pdo->prepare("SELECT COUNT(*) AS comment_count FROM comments WHERE question_id = :question_id");
                    $stmt->bindParam(':question_id', $question_id);
                    $stmt->execute();
                    $result = $stmt->fetch();

                    if ($result['comment_count'] > 0) {
                        // Delete the comments associated with the question
                        $stmt = $pdo->prepare("DELETE FROM comments WHERE question_id = :question_id");
                        $stmt->bindParam(':question_id', $question_id);
                        $stmt->execute();
                    }

                    // Get the image ID associated with the question
                    $stmt = $pdo->prepare("SELECT image_id FROM questions WHERE question_id = :question_id");
                    $stmt->bindParam(':question_id', $question_id);
                    $stmt->execute();
                    $result = $stmt->fetch();

                    if ($result['image_id'] !== null) {
                        $image_id = $result['image_id'];

                        // Delete the question
                        $stmt = $pdo->prepare("DELETE FROM questions WHERE question_id = :question_id");
                        $stmt->bindParam(':question_id', $question_id);
                        $stmt->execute();

                        // Delete the image record from the images table
                        $stmt = $pdo->prepare("DELETE FROM images WHERE image_id = :image_id");
                        $stmt->bindParam(':image_id', $image_id);
                        $stmt->execute();

                        // Delete the image file
                        $stmt = $pdo->prepare("SELECT image_path FROM images WHERE image_id = :image_id");
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
                }

                // Delete questions in the module
                $stmt = $pdo->prepare("DELETE FROM questions WHERE module_id = :module_id");
                $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
                $stmt->execute();

                // Delete the module
                $stmt = $pdo->prepare("DELETE FROM modules WHERE module_id = :module_id");
                $stmt->bindParam(':module_id', $module_id, PDO::PARAM_INT);
                $stmt->execute();

                $success['success_delete'] = 'Module deleted successfully!';
                $_SESSION['success'] = $success;
                header('Location: ../templates/adminmodule.html.php');
                exit();
            } catch (PDOException $e) {
                $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
                $_SESSION['errors'] = $errors;
                header('Location: ../templates/adminmodule.html.php');
                exit;
            }
        }
    }
} catch (Exception $e) {
    $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    header('Location: ../templates/adminmodule.html.php');
    die();
}