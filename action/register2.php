<?php
ob_start();
if($_SERVER['REQUEST_METHOD']==="POST"){			
	$email = $_POST['email'];
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	try {
		require_once '../config/function.php';
		require_once '../config/database.php';
		require_once '../config/register_function_c.php';
		require_once '../config/register_function_m.php';
		
		// Error handlers
		$errors = [];
		if (input_empty($username, $password, $email)){
			$errors["empty_input"] = "Please fill in all fields";
		}	
		if (email_invalid($email)){
			$errors["invalid_email"] = "Invalid email";
		}
		if (used_username ($pdo, $username)){
			$errors["used_username"] = "Username already used";
		}
		if (used_email ( $pdo, $email)){
			$errors["used_email"] = "Email already used";
		}
		
		require_once '../config/config_session.php';

		if($errors){
			$_SESSION['errors_register'] = $errors;
			header("location: ../templates/register.html.php");
			die();
		}else{
		$_SESSION['admin'] = false;
		$user_id = create_user( $pdo, $username, $password, $email);
		header("location:../templates/homepage.html.php");
		$_SESSION['username'] = $username;
		$_SESSION['user_id'] = $user_id;
		$pdo = null;
		$stmt = null;
		die();	
	}
		
	} catch (PDOException $e) {
		$errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
		$_SESSION['errors'] = $errors;
		header("location: ../templates/register.html.php");
		die();
	}}





	