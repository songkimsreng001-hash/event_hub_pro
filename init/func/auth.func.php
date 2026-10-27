<?php
/**
 * Authentication & Account Functions
 */

function usernameExists($username)
{
    global $db;
    $query = $db->prepare('SELECT id FROM tbl_users WHERE username = ?');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();
    return $result->num_rows > 0;
}

function registerUser($name, $username, $passwd)
{
    global $db;
    $hashedPasswd = password_hash($passwd, PASSWORD_DEFAULT);
    $level = 'user';
    $defaultPhoto = './assets/images/Profile_PNG.png';
    $query = $db->prepare('INSERT INTO tbl_users (name, username, passwd, level, photo) VALUES (?, ?, ?, ?, ?)');
    $query->bind_param('sssss', $name, $username, $hashedPasswd, $level, $defaultPhoto);
    $query->execute();
    return $db->affected_rows > 0;
}

function logUserIn($username, $passwd)
{
    global $db;
    $query = $db->prepare('SELECT * FROM tbl_users WHERE username = ? LIMIT 1');
    $query->bind_param('s', $username);
    $query->execute();
    $result = $query->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_object();
        // Support both hashed password and legacy plain-text password
        if (password_verify($passwd, $user->passwd) || $user->passwd === $passwd) {
            // If legacy plain-text, upgrade to bcrypt on the fly
            if ($user->passwd === $passwd) {
                $newHash = password_hash($passwd, PASSWORD_DEFAULT);
                $upQuery = $db->prepare('UPDATE tbl_users SET passwd = ? WHERE id = ?');
                $upQuery->bind_param('si', $newHash, $user->id);
                $upQuery->execute();
            }
            return $user;
        }
    }
    return false;
}

function loggedInUser()
{
    global $db;
    if (!isset($_SESSION['user_id'])) {
        return null;
    }
    $user_id = (int)$_SESSION['user_id'];
    $query = $db->prepare('SELECT * FROM tbl_users WHERE id = ? LIMIT 1');
    $query->bind_param('i', $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}

function isAdmin()
{
    $user = loggedInUser();
    return ($user && $user->level === 'admin');
}

function isUserHasPassword($passwd)
{
    $user = loggedInUser();
    if (!$user) {
        return false;
    }
    return password_verify($passwd, $user->passwd) || $user->passwd === $passwd;
}

function setUserNewPassword($passwd)
{
    global $db;
    $user = loggedInUser();
    if (!$user) {
        return false;
    }
    $hashed = password_hash($passwd, PASSWORD_DEFAULT);
    $query = $db->prepare("UPDATE tbl_users SET passwd = ? WHERE id = ?");
    $query->bind_param('si', $hashed, $user->id);
    $query->execute();
    return $db->affected_rows >= 0;
}

// Retain typo alias for backwards compatibility
function setUserNewPassowrd($passwd)
{
    return setUserNewPassword($passwd);
}

function changeProfileImage($image)
{
    global $db;
    $user = loggedInUser();
    if (!$user) {
        return false;
    }
    $image_path = uploadImage($image);
    if ($image_path) {
        // Delete old uploaded image if not default
        if ($user->photo && file_exists($user->photo) && strpos($user->photo, 'Profile_PNG.png') === false) {
            @unlink($user->photo);
        }
        $query = $db->prepare('UPDATE tbl_users SET photo = ? WHERE id = ?');
        $query->bind_param('si', $image_path, $user->id);
        $query->execute();
        return true;
    }
    return false;
}

function deleteProfileImage()
{
    global $db;
    $user = loggedInUser();
    if (!$user) {
        return false;
    }
    if ($user->photo && file_exists($user->photo) && strpos($user->photo, 'Profile_PNG.png') === false) {
        @unlink($user->photo);
    }
    $default = './assets/images/Profile_PNG.png';
    $query = $db->prepare('UPDATE tbl_users SET photo = ? WHERE id = ?');
    $query->bind_param('si', $default, $user->id);
    $query->execute();
    return true;
}

function uploadImage($image)
{
    $img_name = $image['name'] ?? '';
    $img_size = $image['size'] ?? 0;
    $tmp_name = $image['tmp_name'] ?? '';
    $error    = $image['error'] ?? UPLOAD_ERR_NO_FILE;

    $dir = './assets/images/';
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }

    $allow_exs = ['jpg', 'png', 'jpeg', 'webp'];
    $image_ex = pathinfo($img_name, PATHINFO_EXTENSION);
    $image_lowercase_ex = strtolower($image_ex);

    if ($error !== UPLOAD_ERR_OK) {
        throw new Exception('Image upload failed with error code: ' . $error);
    }

    if (!in_array($image_lowercase_ex, $allow_exs)) {
        throw new Exception('Invalid file extension! Allowed formats: JPG, JPEG, PNG, WEBP.');
    }

    // Limit to 2MB (2,097,152 bytes)
    if ($img_size > 2097152) {
        throw new Exception('File size is too large! Maximum allowed size is 2MB.');
    }

    $new_image_name = uniqid("PI-") . '.' . $image_lowercase_ex;
    $image_path = $dir . $new_image_name;

    if (!move_uploaded_file($tmp_name, $image_path)) {
        throw new Exception('Failed to move uploaded file to destination directory.');
    }

    return $image_path;
}
