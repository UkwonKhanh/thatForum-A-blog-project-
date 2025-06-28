<?php 
require_once '../config/config_session.php';
require_once "../templates/register.html.php";
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
						<h3 class="text-primary">Register</h3>
						<hr/>
						<form action="../action/register2.php" method="post">
							<div class="form-group">
								<label for="username">Username:</label>
								<input type="text" class="form-control <?php echo (isset($_SESSION['errors_register']['used_username'])) ? 'is-invalid' : ''?>" id="username" name="username" />
								<div class="invalid-feedback">
									<?php echo ($_SESSION['errors_register']['used_username']); ?>
								</div>
							</div>
							<div class="form-group">
								<label for="email">Email address:</label>
								<input type="email" class="form-control <?php echo (isset($_SESSION['errors_register']['invalid_email']) || isset($_SESSION['errors_register']['used_email']))  ? 'is-invalid' : ''?> " id="email" name="email" />
								<div class="invalid-feedback">
									<?php if (isset($_SESSION['errors_register']['invalid_email'])): ?>
									<?php echo ($_SESSION['errors_register']['invalid_email']); else: ?>
									<?php echo ($_SESSION['errors_register']['used_email']);  endif;?>
								</div>
							</div>
							<div class="form-group">
								<label for="password">Password:</label>
								<input type="password" class="form-control" id="password" name="password" />
							</div>
							<button type="submit" class="btn btn-primary btn-block" name="register">Register</button>
							<p class="text-center">Already have an account? <a href="../templates/login.html.php">Login</a></p>
						</form>
						<!-- display empty input message -->
						<?php if (isset($_SESSION['errors_register']['empty_input']) && !empty($_SESSION['errors_register']['empty_input'])): ?>
						<div class="alert alert-danger">
							<ul class="mb-0">
								<li><?php echo $_SESSION['errors_register']['empty_input'] ?></li>
							</ul>
						</div>
						<?php unset($_SESSION['errors']); ?> <?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php unset($_SESSION['errors_register']);  ?>
	<?php require_once "footer.php"?>
