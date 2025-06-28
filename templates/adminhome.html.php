<?php
require_once "../action/homepage.php";
require_once '../config/database.php';
require_once "../templates/header.php";
?>

<!DOCTYPE html>
<html>
<head>
  <title>Homepage</title>
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
        <?php foreach ($_SESSION['success'] as $success):?>
        <li><?= $success?></li>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['success']); ?> <?php endif; ?>
  <!-- Main content -->
  <div class="container">
    <h1 class="display-4 mb-5">Welcome, Admin <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
    <h2 class="display-5 mb-4">Top Questions</h2>
    <?php foreach ($questionsWithComments as $question): ?>
      <div class="card border-light mb-5">
      <div class="card-header bg-warning text-dark-emphasis">  
          <h3 class="card-title"><?php echo htmlspecialchars($question['question_title'], ENT_QUOTES, "UTF-8"); ?></h3>
          <div class="question-actions">
            <button type="button" class="btn btn-sm btn-primary" onclick="window.location.href='updatequestion.html.php?question_id=<?php echo $question['question_id']; ?>'">Update</button>
            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete(<?php echo $question['question_id']; ?>)">Delete</button>
          </div>
          </div>
          <div class="card-body">
          <div class="question-meta">
            <p>Created by: <?php echo htmlspecialchars($question['question_creator_username'], ENT_QUOTES, "UTF-8"); ?></p>
            <p>Module: <?php echo htmlspecialchars($question['module_code'], ENT_QUOTES, "UTF-8"); ?> - <?php echo htmlspecialchars($question['module_name'], ENT_QUOTES, "UTF-8"); ?>  </p>
            <p>Created at: <?php echo htmlspecialchars($question['question_created_at'], ENT_QUOTES, "UTF-8"); ?></p>
          </div>
          <hr>
          <div class="question-content">
            <p><?php echo htmlspecialchars($question['question_content'], ENT_QUOTES, "UTF-8"); ?></p>
            <?php if (!empty($question['image_path'])): ?>
              <img src="<?php echo htmlspecialchars($question['image_path'], ENT_QUOTES, "UTF-8"); ?>" alt="<?php echo htmlspecialchars($question['image_alt'], ENT_QUOTES, "UTF-8"); ?>" class="img-fluid mb-3">  
            <?php endif; ?>
          </div>
          <hr>
          <div class="comment-form">
            <form action="../action/comment.php" method="post">
              <input type="hidden" name="question_id" value="<?php echo $question['question_id']; ?>">
              <textarea name="comment" id="comment" class="form-control comment-input <?php echo (isset($_SESSION['errors']['empty_comment'])) ? 'is-invalid' : ''; ?>" placeholder="Add a comment..."></textarea>
              <div class="invalid-feedback">
                      Please enter something in the comment.
              </div>
              <button type="submit" class="btn btn-primary">Comment</button>
            </form>
          </div>
          <div class="comments">
            <?php if (!empty($question['comments'])): ?>
              <h4>Comments</h4>
              <ul class="list-group">
                <?php foreach ($question['comments'] as $comment): ?>
                    <li class="list-group-item "><?php echo $comment['comment']; ?> by <?php echo $comment['comment_creator']; ?></li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <li>No comments available.</li>
            <?php endif; ?>
          </div>
          </div>
      </div>
    <?php endforeach; unset($_SESSION['errors']) ?>
  </div>
  <script>
    function confirmDelete(questionId) {
      if (confirm('Are you sure you want to delete this question?')) {
        window.location.href = '../action/deletequestion.php?question_id=' + questionId;
      }
    }
  </script>
<?php require_once "../templates/footer.php"; ?>