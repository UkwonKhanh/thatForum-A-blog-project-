<?php
ob_start();
require_once '../config/config_session.php';
if (!isset($_SESSION['user_id'])) {
    header('Location: ../action/index.php');
    exit();
}
try {
    require_once '../config/database.php';
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];
    $errors = [];
    
    // Fetch user's posts/questions
    $sql = "SELECT 
    q.*, 
    u.username AS question_creator_username, 
    m.module_code, 
    i.image_path, 
    i.image_alt, 
    c.comment, 
    c.created_at AS comment_created_at, 
    cu.username AS comment_creator 
FROM 
    questions q 
INNER JOIN users u ON q.user_id = u.user_id 
INNER JOIN modules m ON q.module_id = m.module_id 
LEFT JOIN images i ON q.image_id = i.image_id 
LEFT JOIN comments c ON q.question_id = c.question_id 
LEFT JOIN users cu ON c.user_id = cu.user_id 
WHERE 
    q.user_id = :user_id 
ORDER BY 
    q.updated_at DESC, 
    c.created_at DESC;";
    $stmt = $pdo->prepare($sql);
    $stmt -> bindParam(":user_id", $user_id);
    $stmt->execute();
    $tbquestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

$questionsWithComments = [];
foreach ($tbquestions as $question) {
    $questionId = $question['question_id'];
    if (!isset($questionsWithComments[$questionId])) {
        $questionsWithComments[$questionId] = $question;
        $questionsWithComments[$questionId]['comments'] = [];
    }
    if ($question['comment']) {
        $questionsWithComments[$questionId]['comments'][] = [
            'comment' => $question['comment'],
            'created_at' => $question['comment_created_at'],
            'comment_creator' => $question['comment_creator']
        ];
    }
}

} catch (PDOException $e) {
    $errors['exception'] = "Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!";
    $_SESSION['errors'] = $errors;
    die();
}

