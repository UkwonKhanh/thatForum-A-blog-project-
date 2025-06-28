<?php
function input_empty(string $username, string $pwd, $email){
    if (empty($username)||empty($pwd) || empty($email)){
        return true;
    }else{
        return false;
    }}


function email_invalid (string $email){
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)){
        return true;
    }else{
        return false;
    }
}

function used_username (object $pdo, string $username){
    if (get_user( $pdo, $username)){
        return true;
    }else{
        return false;
    }
}

function used_email (object $pdo, string $email){
    if (get_email($pdo,  $email)){
        return true;
    }else{
        return false;
    }
}


function create_user (object $pdo,string $username, string $pwd,string $email){
  $result = set_user( $pdo, $username,  $pwd, $email);
    if ($result){
        return $result;
    }else{
        return false;
    }
}


  