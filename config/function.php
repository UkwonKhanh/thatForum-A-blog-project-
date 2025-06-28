<?php
declare(strict_types=1);
function is_input_empty(string $username, string $pwd){
    if (empty($username)||empty($pwd)){
        return true;
    }else{
        return false;
    }
}
function logger($log){
    $time = date('m/d/y h:iA', time());
    $contents = file_get_contents('log.txt');
    $contents .= "$time\t$log\r";
    file_put_contents('log.txt', $contents);
}

function empty_title (string $title){
    if (empty($title)){
        return true;
    }else{
        return false;
    }
}

function empty_content (string $content){
    if (empty($content)){
        return true;
    }else{
        return false;
    }
}

function empty_module (int $module){
    if (empty($module)){
        return true;
    }else{
        return false;
    }
}

function get_question_details_by_id($question_id) {
    global $pdo; 
    try {
        $stmt = $pdo->prepare("SELECT q.*, i.image_path, i.image_alt
                                FROM questions q
                                LEFT JOIN images i ON q.image_id = i.image_id
                                WHERE q.question_id = :question_id;");
        $stmt->bindParam(':question_id', $question_id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Handle the exception
        logger("Error to get question details: " . $e->getMessage());
        return false;
    }
}


function get_user_details ($user_id){
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE users.user_id = :user_id");
        $stmt->bindParam(':user_id', $user_id, PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return die ("Error to get question details: " . $e->getMessage());
    }
}

function get_module_details($user_id){
    global $pdo;
    try {
        $stmt = $pdo->prepare("SELECT * FROM modules WHERE modules.module_id = :module_id");
        $stmt->bindParam(':module_id', $user_id, PDO::PARAM_INT);
        $stmt -> execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        return die ("Error to get question details: " . $e->getMessage());
    }
}

function get_user_login ($username){
    $stmt = $pdo->prepare('SELECT * FROM users WHERE username = :username');
    $stmt->bindParam(':username', $username);
    $stmt->execute(); 
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    return $user;
}