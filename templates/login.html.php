<?php 
require_once "../action/login.php";
require_once '../config/config_session.php';
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" name="viewport" content="width=device-width, initial-scale=1"/>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="../style/style.css"/>
	</head>
<body>
<!-- display errors message -->
<?php if (isset($_SESSION['errors']['exception']) && !empty($_SESSION['errors']['exception'])): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
        <?php foreach ($_SESSION['errors'] as $error): ?>
            <li><?= $error ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php unset($_SESSION['errors']); ?> <?php endif; ?>

	<div class="container">
		<div class="row justify-content-center">
			<div class="col-md-6">
				<div class="card">
					<div class="card-body">
						<h3 class="text-primary">Login</h3>
						<hr />
						<form action="../action/login.php" method="post">
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="text" class="form-control <?php echo (isset($_SESSION['errors_login']['login_incorrect_user'])) ? 'is-invalid' : ''?> " id="username" name="username" />
							<div class="invalid-feedback">
								<?php echo ($_SESSION['errors_login']['login_incorrect_user']); ?>
							</div>
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" class="form-control <?php echo (isset($_SESSION['errors_login']['login_incorrect_pwd'])) ? 'is-invalid' : '' ?> " id="password" name="password" />
							<div class="invalid-feedback">
								<?php echo ($_SESSION['errors_login']['login_incorrect_pwd']); ?>
							</div>
							</div>
							<button type="submit" class="btn btn-primary btn-block" name="login">Login</button>
							<p class="text-center">Don't have an account? <a href="../templates/register.html.php">Register</a></p>
						</form>
						<!-- display empty input message -->
						<?php if (isset($_SESSION['errors_login']['empty_input']) && !empty($_SESSION['errors_login']['empty_input'])): ?>
						<div class="alert alert-danger">
							<ul class="mb-0">
								<li><?php echo $_SESSION['errors_login']['empty_input'] ?></li>
							</ul>
						</div>
						<?php unset($_SESSION['errors']); ?> <?php endif; ?>
					</div>
				</div>
				
			</div>
		</div>
	</div>
	<?php unset($_SESSION['errors_login'])?>
	<?php require_once "footer.php"?>
