<?php
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id && $isAdmin) {
    if (deleteEvent($id)) {
        setFlash('success', 'Event has been permanently deleted.', 'Event Deleted');
    } else {
        setFlash('error', 'Failed to delete event. Please try again.', 'Error');
    }
}

redirect('./?page=events');

