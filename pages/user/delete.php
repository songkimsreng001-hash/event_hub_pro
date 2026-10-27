<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id && $isAdmin) {
    if (deleteUser($id)) {
        setFlash('success', 'User account and registrations have been removed.', 'User Deleted');
    } else {
        setFlash('error', 'Cannot delete user. Admin accounts cannot be deleted.', 'Error');
    }
}

redirect('./?page=user/list');
