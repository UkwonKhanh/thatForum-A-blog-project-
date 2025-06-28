<?php
require_once "../action/viewmodule.php";
require_once "../templates/header.php";
?>
<!DOCTYPE html>
<html>
<head>
  <title>Admin Module</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
  <!-- display errors message -->
  <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
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
    <?php foreach ($_SESSION['success'] as $success): ?>
      <li><?= $success ?></li>
    <?php endforeach; ?>
  </div>
  <?php unset($_SESSION['success']); ?> <?php endif; ?>
  <!-- Main content -->
  <div class="container">
    <h1 class="display-4 mb-5">Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2 class="display-5 mb-4">Modules</h2>
    <a href="addmodule.html.php" class="btn btn-sm btn-success mb-3">Add New Module</a>
    <div class="row">
      <?php foreach ($modules as $module): ?>
      <div class="col-md-4">
        <div class="card border-light mb-3">
          <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($module['module_name'], ENT_QUOTES, "UTF-8"); ?></h5>
            <p class="card-text"><?= htmlspecialchars($module['module_code'], ENT_QUOTES, "UTF-8"); ?></p>
            <div class="question-actions">
              <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='updatemodule.html.php?module_id=<?php echo $module['module_id']; ?>'">Update</button>
              <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $module['module_id']; ?>)">Delete</button>
            </div>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <script>
  function confirmDelete(moduleId) {
    if (confirm('Are you sure you want to delete this module?')) {
      window.location.href = '../action/deletemodule.php?module_id=' + moduleId;
    }
  }
  </script>
<?php require_once "../templates/footer.php"; ?>