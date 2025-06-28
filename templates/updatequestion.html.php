<?php
require_once "../config/config_session.php";
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit();
}
require_once '../config/database.php';
require_once "../config/function.php";

if (isset($_GET['question_id'])){
$question_id = $_GET['question_id']; 
$question = get_question_details_by_id($question_id);}
require_once '../action/viewmodule.php';
require_once 'header.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <title>Edit Question</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

  <link rel="stylesheet" href="../style/style.css">
</head>
<body>
  <!-- errors update account -->
<?php if (isset($_SESSION['errors']['errors_update'])): ?>
  <div class="alert alert-danger" role="alert">
  <p>Error: <?php echo $_SESSION['errors']['errors_update']; ?></p>
  </div>
<?php endif; ?>


<div class="container mt-5">
  <h2>Update Question</h2>
  <form action="../action/updatequestion.php" method="post" enctype="multipart/form-data">
    <input type="hidden" name="question_id" value="<?php echo (isset($question['question_id'])) ? $question['question_id'] : ''; ?>">
    <div class="mb-3">
      <label for="questionTitle" class="form-label">Question Title</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty title'])) ? 'is-invalid' : ''; ?>" id="questionTitle" name="questionTitle" rows="2"><?php echo (isset($question['title'])) ? htmlspecialchars($question['title']) : ''; ?></textarea>
      <div class="invalid-feedback">
        Please enter a question title.
      </div>
    </div>
    <div class="mb-3">
      <label for="questionContent" class="form-label">Question Content</label>
      <textarea class="form-control <?php echo (isset($_SESSION['errors']['empty content'])) ? 'is-invalid' : ''; ?>" id="questionContent" name="questionContent" rows="3" title="Enter your question here"><?php echo (isset($question['content'])) ? htmlspecialchars($question['content']) : ''; ?></textarea>
      <div class="invalid-feedback">
        Please enter question content.
      </div>
    </div>
    <div class="mb-3">
      <label for="moduleCode" class="form-label">Module Code</label>
      <select class="form-select <?php echo (isset($_SESSION['errors']['empty module'])) ? 'is-invalid' : ''; ?>" id="module_id" name="module_id">
        <option value="">Select Module</option>
        <?php foreach ($modules as $module): ?>
          <option value="<?php echo $module['module_id']; ?>" <?php echo
 (isset($question['module_id']) && $question['module_id'] == $module['module_id']) ? 'selected' : ''; ?>>
            <?php echo $module['module_code'] . ' - ' . $module['module_name']; ?>
          </option>
        <?php endforeach; ?>
      </select>
      <div class="invalid-feedback">
        Please select a module.
      </div>
    </div>
    <div class="mb-3">
      <label for="questionImage" class="form-label">Image (Optional)</label>
      <input type="file" class="form-control <?php echo (isset($_SESSION['errors']['errors_image'])) ? 'is-invalid' : ''; ?>" id="questionImage" name="questionImage">
      <?php if (isset($question['image_path']) && !empty($question['image_path'])) : ?>
        <br>
        <label for="previousImage" class="form-label">Previous Image </label>
        <img src="<?php echo $question['image_path']; ?>" id="previousImage" alt="<?php echo $question['image_alt']; ?>" style="width: 200px;">
      <?php endif; ?>
    </div>
    <button type="submit" class="btn btn-primary">Save</button>
  </form>
</div>
</body>
<?php unset($_SESSION['errors']); ?>
</html>
<?php require_once "footer.php"; ?>