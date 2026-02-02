<?php
function usernameExists($username) {
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ?');
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
    $query = $db->prepare('INSERT INTO tbl_users (name, username, password) VALUES (?, ?, ?)');
    $query->bind_param('sss', $name, $username, $hashedPassword);
    $query->execute();
    if ($query->affected_rows) {
        return true;
    }
    return false;
}
function logUserIn($username, $password) {
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ? AND password = ?');
    $query->bind_param('ss', $username, $password);
    $query->execute();
    if ($query->affected_rows) {
        return true;
    }
    return false;
}

function loggedInUser() {
    global $db;
    if (isset($_SESSION['user_id'])) {
        return null;
        }
        $user_id = $_SESSION['user_id'];
        $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ?');
        $query->bind_param('d', $user_id);
        $query->execute();
        $result = $query->get_result();
        if ($result->num_rows) {
            return $result->fetch_object();
    }
    return null;
}