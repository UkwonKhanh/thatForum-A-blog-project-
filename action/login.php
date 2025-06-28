<?php
ob_start();
if($_SERVER['REQUEST_METHOD']==="POST"){
			
	$username = $_POST['username'];
	$pwd = $_POST['password'];
	
			
      try{
		require_once '../config/database.php';
		require_once "../config/function.php";
	 	
		$errors = [];
		
		if (is_input_empty($username, $pwd)){
			$errors["empty_input"] = "Fill in all fields!";
		}
		
		// User retrieval 
		$stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username"); // "SELECT * FROM users WHERE username = :username
		$stmt->bindParam(':username', $username);
		$stmt->execute();
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		// error login
		if ($username !== $user['username']){
			$errors["login_incorrect_user"] = "Incorrect username!";
		}
		if ($user && !password_verify($pwd, $user['password'])){   // if password is hashed !password_verify($pwd, $user['password']) $pwd !== $user['password']
			$errors["login_incorrect_pwd"] = "Incorrect password!";
		}

		require_once '../config/config_session.php';
		
		if ($errors) {
			$_SESSION['errors_login'] = $errors;
			header ("location: ../templates/login.html.php");
			exit;
		}else{
			try{
			// Check 
			if ($user['is_admin'] == 1) {
				$_SESSION['admin'] = true;
				
				$newSessionId = session_create_id();
				$sessionId = $newSessionId . "_" . $user['user_id'];
				session_id($sessionId);
				
				$_SESSION['username'] = htmlspecialchars($user["username"]);
				$_SESSION['user_id'] = $user['user_id'];
				$_SESSION['last_regeneration'] = time();
				$pdo = null;
				$stmt = null;
				
				header("location: ../templates/adminhome.html.php");
				exit;
			} else {
				$_SESSION['admin'] = false;
				
				$newSessionId = session_create_id();
				$sessionId = $newSessionId . "_" . $user['user_id'];
				session_id($sessionId);

				$_SESSION["user_id"] = $user["user_id"];
				$_SESSION["username"] = htmlspecialchars($user["username"]);
				$_SESSION['last_regeneration'] = time();
				header("location: ../templates/homepage.html.php");
				$pdo = null;
				$stmt = null;
				exit;}
			}catch(PDOException $e){
					$errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
					$_SESSION['errors'] = $errors;
					header ("location: ../templates/login.html.php");
					exit;
				}
			}
}catch(Exception $e){
	$errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
	$_SESSION['errors'] = $errors;
	header ("location: ../templates/login.html.php");
	die();
	}
}
