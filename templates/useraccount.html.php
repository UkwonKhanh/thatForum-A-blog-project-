<?php
require_once "../action/useraccount.php"; 
require_once "../templates/header.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>User Accounts</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
<!-- display success message -->
<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php foreach ($_SESSION['success'] as $success): ?>
        <li><?= $success ?> </li>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['success']); ?> <?php endif; ?>
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
  <h1 class="display-4 mb-5 col-md-6">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
  <h2 class="display-5 mb-4 col-md-6"> Your Account</h2>
    <div class="card border-light mb-3 col-md-6">
      <div class="card-body">
        <div class="d-flex flex-column">
          <p><b>User: </b><?php echo htmlspecialchars($user_account['username'], ENT_QUOTES, "UTF-8"); ?></p>
          <p><b>Email: </b><?php echo htmlspecialchars($user_account['email_address'], ENT_QUOTES, "UTF-8") ?></p>
          <p><b>Password: </b><?php echo htmlspecialchars($user_account['password'], ENT_QUOTES, "UTF-8") ?></p>  
          <div class="question-actions">
            <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='updateaccount.html.php?user_id=<?php echo $_SESSION['user_id']; ?>'">Update</button>
            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $user_account['user_id']; ?>)">Delete</button>
          </div>
        </div>
      </div>
  </div>
</div>
<script>
  function confirmDelete(userId) {
    if (confirm('Are you sure you want to delete your account?')) {
      window.location.href = '../action/deleteaccount.php?user_id=' + userId;
    }
  }
</script>
<?php require_once "footer.php"?>