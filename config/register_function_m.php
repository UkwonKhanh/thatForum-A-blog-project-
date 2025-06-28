<?php
declare(strict_types = 1);

function get_user(object $pdo, string $username): bool {
    $sql = "SELECT COUNT(*) FROM users WHERE username = :username";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':username', $username);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
}

function get_email(object $pdo, string $email): bool {
    try{
        $sql = "SELECT COUNT(*) FROM users WHERE email_address = :email";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        return (int) $stmt->fetchColumn() > 0;
}catch (PDOException $e) {
    header("location: ../templates/register.html.php");
    die("Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!");}}


    function set_user(object $pdo, string $username, string $password, string $email): int {
    // Use this to hash password  
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    try{
    $sql = "INSERT INTO users (username, password, email_address) VALUES (:username, :password, :email)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':username', $username);
    $stmt->bindParam(':password', $hashed_password); // $password ;
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    
    $user_id = $pdo -> lastInsertId();
    return (int) $user_id;
}catch (PDOException $e) {
    header("location: ../templates/register.html.php");
    die("Failed: " . $e ->getMessage() . ". PLEASE CONTACT ADMIN!");
    }}