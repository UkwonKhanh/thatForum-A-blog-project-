<?php
require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
require_once '../config/database.php';
require_once "../config/function.php";


require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0"> 

  <title>Contact</title>
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

<!-- display success message -->
<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php foreach ($_SESSION['success'] as $success):?>
        <?= $success ?>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['success']); ?> <?php endif; ?>

  <!-- Main content -->
  <div class="container mt-5">
    <h2>Send Email to Admin</h2>
    <form action="../action/contact.php" method="post"> <div class="mb-3">
        <div class="mb-3">
            <label for="messageTitle" class="form-label">Message Title</label>
            <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty title'])) ? 'is-invalid' : ''; ?>" id="messageTitle" name="messageTitle" rows="2"></textarea>
        <div class="invalid-feedback">
        <?php echo $_SESSION['errors']['empty title']; ?>
        </div>
        </div>
        <div class="mb-3">
            <label for="message" class="form-label">Message</label>
            <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty content'])) ? 'is-invalid' : ''; ?>" id="message" name="message" rows="3"></textarea> 
            <div class="invalid-feedback">
              <?php echo $_SESSION['errors']['empty content']; ?>
          </div>
          </div>
      <button type="submit" class="btn btn-primary">Send Email</button>
    </form>
  </div>
<?php unset($_SESSION['errors']); ?>
<?php require_once "footer.php"; ?>
