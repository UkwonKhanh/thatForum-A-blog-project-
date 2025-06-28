<?php
ob_start();
// Include the session configuration file
require_once "../config/config_session.php";

// Check if the user is logged in, if not, redirect to the index page
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}

try {
    // Check if the request method is POST
    if ($_SERVER['REQUEST_METHOD'] === "POST") {
        // Include the necessary configuration files
        require_once "../config/database.php";
        require_once "../config/function.php";
        require_once '../config/register_function_c.php';
        require_once '../config/register_function_m.php';

        // Get the user ID from the session
        $user_id = $_SESSION["user_id"];

        // Get the email address, username, and password from the POST request
        $email = $_POST['emailAddress'];
        $username = $_POST['username'];
        $password = $_POST['password'];

        // Initialize arrays to store success and error messages
        $success = [];
        $errors = [];

        // Get the current email address from the database
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id);
        $stmt->execute();
        $current_account = $stmt->fetch(PDO::FETCH_ASSOC);

        // Validate user input
        if (input_empty($username, $password, $email)) {
            // If any of the fields are empty, add an error message
            $errors["empty_input"] = "Please fill in all fields";
        }
        if (email_invalid($email)) {
            // If the email address is invalid, add an error message
            $errors["invalid_email"] = "Invalid email";
        }
        if ($username !== $current_account['username']){
            if (used_username($pdo, $username)) {
                // If the username is already used, add an error message
                $errors["used_username"] = "Username already used";
        }}

        // Check if the email address has been updated
        if ($email !== $current_account['email_address']) {
            // If the email address has been updated, check if it's already used
            if (used_email($pdo, $email)) {
                $errors["used_email"] = "Email already used";
            }
        }

        // If there are any error messages, redirect to the update account page
        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("location: ../templates/updateaccount.html.php");
            exit();
        } else {
            try {
                // Update the user account in the database
                $hash_password = password_hash($password,PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users 
                    SET username = :username, 
                    email_address = :email_address,
                    `password` = :pwd, 
                    updated_at = NOW()
                    WHERE user_id = :user_id");
                $stmt->bindParam(':user_id', $user_id, PDO::PARAM_STR);
                $stmt->bindParam(':username', $username, PDO::PARAM_STR);
                $stmt->bindParam(':email_address', $email, PDO::PARAM_STR);
                $stmt->bindParam(':pwd', $hash_password, PDO::PARAM_STR);
                $stmt->execute();

                // Add a success message
                $success['succes_update'] = 'Update account successfully!';
                $_SESSION['success'] = $success;
                header('Location: ../templates/useraccount.html.php');
                exit();
            } catch (PDOException $e) {
                // If there's an error updating the database, add an error message
                $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
                $_SESSION['errors'] = $errors;
                header('Location: ../templates/useraccount.html.php');
                exit;
            }
        }
    }
} catch (Exception $e) {
    // If there's an error, add an error message
    $errors['exception'] = "Failed: " . $e->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    header('Location: ../templates/useraccount.html.php');
    die();
}