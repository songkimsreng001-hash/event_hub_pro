<?php
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;

if ($event_id && $user) {
    if (cancelRegistration($event_id, $user->id)) {
        setFlash('info', 'Your event registration has been cancelled.', 'Booking Cancelled');
    } else {
        setFlash('error', 'Unable to cancel registration. Please try again.', 'Error');
    }
}

redirect('./?page=my-registrations');
