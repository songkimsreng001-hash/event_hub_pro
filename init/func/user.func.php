<?php
/**
 * User Management Functions (Admin & User CRUD)
 */

function createUser($name, $username, $passwd, $photo = null, $level = 'user')
{
    global $db;

    $image_path = './assets/images/Profile_PNG.png';
    if (!empty($photo['name'])) {
        $image_path = uploadImage($photo);
    }

    $hashedPasswd = password_hash($passwd, PASSWORD_DEFAULT);
    $query = $db->prepare('INSERT INTO tbl_users (name, username, passwd, level, photo) VALUES (?, ?, ?, ?, ?)');
    $query->bind_param('sssss', $name, $username, $hashedPasswd, $level, $image_path);
    $query->execute();
    return $db->affected_rows > 0;
}

function getUsers($search = null)
{
    global $db;
    if ($search !== null && trim($search) !== '') {
        $searchTerm = '%' . trim($search) . '%';
        $query = $db->prepare('SELECT * FROM tbl_users WHERE level <> "admin" AND (name LIKE ? OR username LIKE ?) ORDER BY id DESC');
        $query->bind_param('ss', $searchTerm, $searchTerm);
        $query->execute();
        return $query->get_result();
    }

    $query = $db->prepare('SELECT * FROM tbl_users WHERE level <> "admin" ORDER BY id DESC');
    $query->execute();
    return $query->get_result();
}

function readUser($id)
{
    global $db;
    $id = (int)$id;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ? LIMIT 1');
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if($result->num_rows > 0){
        return $result->fetch_object();
    }
    return null;
}

function deleteUser($id)
{
    global $db;
    $id = (int)$id;
    $target = readUser($id);
    if (!$target || $target->level === 'admin') {
        return false;
    }

    // Delete custom uploaded photo
    if ($target->photo && file_exists($target->photo) && strpos($target->photo, 'Profile_PNG.png') === false) {
        @unlink($target->photo);
    }

    $query = $db->prepare('DELETE FROM tbl_users WHERE id = ?');
    $query->bind_param('i', $id);
    $query->execute();
    return $db->affected_rows > 0;
}

function updateUser($id, $name, $username, $passwd = '', $photo = null, $level = null)
{
    global $db;
    $id = (int)$id;
    $existing = readUser($id);
    if (!$existing) {
        return false;
    }

    $image_path = null;
    if (!empty($photo['name'])) {
        $image_path = uploadImage($photo);
        // Remove old image if replaced and not default
        if ($existing->photo && file_exists($existing->photo) && strpos($existing->photo, 'Profile_PNG.png') === false) {
            @unlink($existing->photo);
        }
    }

    // Build dynamic update query
    $fields = ['name = ?', 'username = ?'];
    $types  = 'ss';
    $params = [$name, $username];

    if (!empty($passwd)) {
        $fields[] = 'passwd = ?';
        $types   .= 's';
        $params[] = password_hash($passwd, PASSWORD_DEFAULT);
    }

    if ($image_path !== null) {
        $fields[] = 'photo = ?';
        $types   .= 's';
        $params[] = $image_path;
    }

    if ($level !== null && in_array($level, ['admin', 'user'])) {
        $fields[] = 'level = ?';
        $types   .= 's';
        $params[] = $level;
    }

    $types   .= 'i';
    $params[] = $id;

    $sql = 'UPDATE tbl_users SET ' . implode(', ', $fields) . ' WHERE id = ?';
    $query = $db->prepare($sql);
    $query->bind_param($types, ...$params);
    $query->execute();
    return $db->affected_rows >= 0;
}
