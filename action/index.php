<?php
ob_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once '../config/database.php'; // Database connection
require_once '../config/function.php';
include '../templates/header.php';?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Welcome</title>
</head>
<body>
    <!-- display success message -->
<?php if (isset($_SESSION['success']) && !empty($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php foreach ($_SESSION['success'] as $success):?>
        <li><?= $success ?> </li>
        <?php endforeach; ?>
    </div>
    <?php unset($_SESSION['success']); ?> <?php endif; ?>
    <div class = "d-flex flex-column align-items-start vh-100">
        <h1 class="display-1 ">Welcome to ThatForum </h1>
        
</div>
</body>
</html>
<?php include '../templates/footer.php';?>