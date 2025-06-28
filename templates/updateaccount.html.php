<?php
require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
require_once '../config/database.php';
require_once "../config/function.php";

if (isset($_GET['user_id'])){
  $user_id = $_GET['user_id']; 
  $user = get_user_details($user_id);
}
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Edit Account</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="../style/style.css">
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

<?php if (isset($_SESSION['errors']['empty_input']) && !empty($_SESSION['errors']['empty_input'])): ?>
<div class="alert alert-danger">
    <ul class="mb-0">
            <li><?= $_SESSION['errors']['empty_input'] ?></li>
    </ul>
</div>
<?php unset($_SESSION['errors']); ?> <?php endif; ?>


<div class="container mt-5">
  <h2>Update Account </h2>
  <form action="../action/updateaccount.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="user_id" value="<?php echo (isset($user['user_id'])) ? $user['user_id'] : ''; ?>">
    <div class="mb-3">
      <label for="username" class="form-label">Username</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['used_username'])) ? 'is-invalid' : ''; ?>" id="username" name="username" rows="2"><?php echo (isset($user['username'])) ? htmlspecialchars($user['username']) : ''; ?></textarea>
      <div class="invalid-feedback">
          <?php echo $_SESSION['errors']['used_username']; ?>
      </div>
    </div>
    <div class="mb-3">
      <label for="emailAddress" class="form-label">Email address</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['invalid_email'])) ? 'is-invalid' : ''; ?>" id="emailAddress" name="emailAddress" rows="3" title="Enter your email here"><?php echo (isset($user['email_address'])) ? htmlspecialchars($user['email_address']) : ''; ?></textarea>
      <div class="invalid-feedback">
      <?php if (isset($_SESSION['errors']['invalid_email'])): ?>
        <?php echo $_SESSION['errors']['invalid_email']; ?>
        <?php  else:?>
          <?php echo $_SESSION['errors']['used_email']; endif;?>
      </div>
    </div>
    <div class="mb-3">
      <label for="password" class="form-label">Password</label>
      <textarea type = "password"class="form-control <?php echo (isset($_SESSION['errors']['empty_input'])) ? 'is-invalid' : ''; ?>" id="password" name="password" rows="3" title="Enter your password here"><?php echo (isset($user['password'])) ? htmlspecialchars($user['password']) : ''; ?></textarea>
      <div class="invalid-feedback">
        Please enter password.
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
<?php unset($_SESSION['errors']); ?> 
<?php require_once "footer.php"; ?>