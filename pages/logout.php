<?php
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}
setFlash('info', 'You have been logged out successfully.', 'Logged Out');
redirect('./?page=login');
