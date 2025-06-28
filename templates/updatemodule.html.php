<?php
require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
require_once '../config/database.php';
require_once "../config/function.php";

if (isset($_GET['module_id'])){
  $module_id = $_GET['module_id']; 
  $modules = get_module_details($module_id);
}
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Edit Module</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
<!-- Display errors  -->
<?php if (isset($_SESSION['errors']['exception'])): ?>
  <p>Error: <?php echo $_SESSION['errors']['exception']; ?></p>
<?php endif; ?>


<div class="container mt-5">
  <h2>Update Modules </h2>
  <form action="../action/updatemodule.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="module_id" value="<?php echo (isset($modules['module_id'])) ? $modules['module_id'] : ''; ?>">
    <div class="mb-3">
      <label for="moduleCode" class="form-label">Module code</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty_module_code'])) ? 'is-invalid' : ''; ?>" id="moduleCode" name="moduleCode" rows="2"><?php echo (isset($modules['module_code'])) ? htmlspecialchars($modules['module_code']) : ''; ?></textarea>
      <div class="invalid-feedback">
        Please enter a module code.
      </div>
    </div>
    <div class="mb-3">
      <label for="moduleName" class="form-label">Module Name</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty_module_name'])) ? 'is-invalid' : ''; ?>" id="emailAddress" name="moduleName" rows="3" title="enter module name here"><?php echo (isset($modules['module_name'])) ? htmlspecialchars($modules['module_name']) : ''; ?></textarea>
      <div class="invalid-feedback">
        Please enter module name.
      </div>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
</body>
<?php unset($_SESSION['errors']); ?> 
</html>
<?php require_once "footer.php"; ?>