<?php
function usernameExists($$username) {
    global $db;
    $query = $db->prepare('SELECT * FROM users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    if($result->num_rows) {
    return true;
    } 
    return false;
} 



function registerUser($name, $username, $password) {
    global $db;
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    $query = $db->prepare('INSERT INTO users (name, username, password) VALUES (?, ?, ?)');
    $query->bind_param('sss', $name, $username, $hashedPassword);
    return $query->execute();
}