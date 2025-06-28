<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ThatForum</title>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container-fluid">
        <nav class="navbar navbar-expand-lg navbar-light bg-light d-flex justify-content-between">
                <?php if (isset($_SESSION['user_id']) && isset($_SESSION['username'])): ?>
                    <?php if ($_SESSION['admin'] == true): ?>
                        <a class="navbar-brand" href="../templates/adminhome.html.php">ThatForum</a>
                    <?php else: ?>
                        <a class="navbar-brand" href="../templates/homepage.html.php">ThatForum</a>
                    <?php endif; ?>
                    <?php else: ?>
                        <a class="navbar-brand" href="../action/index.php">ThatForum</a>
                    <?php endif; ?>
            <div class="collapse navbar-collapse">
                <ul class="navbar-nav">
                <?php if (isset($_SESSION['user_id'])): ?>
                        <?php if ($_SESSION['admin'] == true): ?>
                            <li>
                                <a class="nav-link" href="../templates/adminhome.html.php">Admin Home</a>
                            </li>
                            <li>
                                <a class="nav-link" href="../templates/adminmodule.html.php">Manage Modules</a>
                            </li>
                            <li class="nav-item">
                                <a class = "nav-link" href="../templates/postquestion.html.php">Post</a>
                            </li>
                            <li>
                                <a class="nav-link" href="../action/logout.php">Log out</a>
                            </li>
                        <?php else: ?>
                            <li class="nav-item">
                                <a class="nav-link" href="../templates/homepage.html.php">Homepage</a>
                            </li>
                            <li class="nav-item">
                                <a class = "nav-link" href="../templates/postquestion.html.php">Post question</a>
                            </li>
                            <li class="nav-item">
                                <a class = "nav-link" href="../templates/userquestion.html.php">Your question</a>
                            </li>
                            <li class="nav-item">
                                <a class = "nav-link" href="../templates/contact.html.php">Contact</a>
                            </li>
                            <li class="nav-item">
                                <a class = "nav-link" href="../templates/useraccount.html.php">Your account</a>
                            </li>
                            <li>
                                <a class="nav-link" href="../action/logout.php">Log out</a>
                            </li>
                            <?php endif; ?>
                            <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="../templates/login.html.php">Login</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../templates/register.html.php">Register</a>
                        </li>
                    <?php endif;?>
                </ul>
            </div>
        </nav>
        <div class="mt-3">