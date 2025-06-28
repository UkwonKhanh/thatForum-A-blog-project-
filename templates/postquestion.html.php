<?php
require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
require_once '../config/database.php';
require_once '../action/postquestion.php'; 
require_once '../action/viewmodule.php';
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Post Question</title>
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

<div class="container mt-5">
  <h2>Post Question</h2>
  <form action="../action/postquestion.php" method="post" enctype="multipart/form-data">
    <div class="mb-3">
      <label for="questionTitle" class="form-label">Question Title</label>
      <input type="text" class="form-control <?php echo (isset($_SESSION['errors']['empty title'])) ? 'is-invalid' : ''; ?>" id="questionTitle" name="questionTitle" >
      <div class="invalid-feedback">
      <?php echo ($_SESSION['errors']['empty title']); ?>
      </div>
    </div>
    <div class="mb-3">
      <label for="questionContent" class="form-label">Question Content</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty content'])) ? 'is-invalid' : ''; ?>" id="questionContent" name="questionContent" rows="3" ></textarea>
      <div class="invalid-feedback">
      <?php echo ($_SESSION['errors']['empty content']); ?>
      </div>
    </div>
    <div class="mb-3">
      <label for="moduleCode" class="form-label">Module Code</label>
      <select class="form-select <?php echo (isset($_SESSION['errors']['empty module'])) ? 'is-invalid' : ''; ?>" id="module_id" name="module_id" >
        <option value="">Select Module</option>
        <?php foreach ($modules as $module): ?>
          <option value="<?php echo $module['module_id']; ?>">
            <?php echo $module['module_code'] . ' - ' . $module['module_name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">
        Please select a module.
      </div>
    </div>
    <div class="mb-3">
      <label for="questionImage" class="form-label ">Image (Optional)</label>
      <input type="file" class="form-control <?php echo (isset($_SESSION['errors']['errors_image'])) ? 'is-invalid' : ''; ?>" id="questionImage" name="questionImage">
    </div>
    <button type="submit" class="btn btn-primary">Ask Question</button>
  </form>
</div>
<?php unset($_SESSION['errors']); ?> 
<?php require_once "footer.php"; ?>