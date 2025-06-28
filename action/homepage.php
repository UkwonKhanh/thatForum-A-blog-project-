<?php
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once '../config/config_session.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['admin'])) {
    header('Location: ../action/index.php');
    exit();
}
try {
    require_once '../config/database.php';
    $is_admin = $_SESSION['admin'];
    $username = $_SESSION['username'];
    $user_id = $_SESSION['user_id'];

    // Fetch user's posts/questions
    $query = "SELECT
    q.question_id AS question_id,
    q.title AS question_title,
    q.content AS question_content,
    q.created_at AS question_created_at,
    u1.username AS question_creator_username,
    m.module_code AS module_code,
    m.module_name AS module_name,
    i.image_path AS image_path,
    i.image_alt AS image_alt,
    c.comment AS comment,
    c.created_at AS comment_created_at,
    u2.username AS comment_creator_username
FROM
    questions q
INNER JOIN users u1 ON q.user_id = u1.user_id
INNER JOIN modules m ON q.module_id = m.module_id
LEFT JOIN images i ON q.image_id = i.image_id
LEFT JOIN comments c ON q.question_id = c.question_id
LEFT JOIN users u2 ON c.user_id = u2.user_id
ORDER BY q.created_at DESC;";
    $stmt = $pdo->prepare($query);
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
            'comment_creator' => $question['comment_creator_username']
        ];
    }
}

} catch (PDOException $e) {
    die("Failed: " . $e ->getMessage());
}

