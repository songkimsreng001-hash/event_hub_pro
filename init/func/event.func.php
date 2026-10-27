<?php
/**
 * Event Management Core Functions
 */

function validateImage($image)
{
    return uploadImage($image);
}

function getAllEvents($search = null, $filter = null)
{
    global $db;
    
    $whereClauses = [];
    $types = '';
    $params = [];

    if (!empty($search)) {
        $term = '%' . trim($search) . '%';
        $whereClauses[] = '(e.title LIKE ? OR e.location LIKE ? OR e.description LIKE ?)';
        $types .= 'sss';
        $params[] = $term;
        $params[] = $term;
        $params[] = $term;
    }

    if ($filter === 'upcoming') {
        $whereClauses[] = 'e.event_date >= NOW()';
    } elseif ($filter === 'past') {
        $whereClauses[] = 'e.event_date < NOW()';
    }

    $whereSql = !empty($whereClauses) ? ' WHERE ' . implode(' AND ', $whereClauses) : '';

    $sql = 'SELECT e.*, 
                   COUNT(CASE WHEN r.status <> "rejected" THEN r.id END) AS reg_count
            FROM tbl_events e
            LEFT JOIN tbl_registrations r ON r.event_id = e.id
            ' . $whereSql . '
            GROUP BY e.id
            ORDER BY e.event_date ASC';

    $query = $db->prepare($sql);
    if (!$query) {
        error_log("Database prepare error in getAllEvents: " . $db->error);
        return false;
    }
    if (!empty($params)) {
        $query->bind_param($types, ...$params);
    }
    $query->execute();
    return $query->get_result();
}

function getEvent($id)
{
    global $db;
    $id = (int)$id;
    $query = $db->prepare(
        'SELECT e.*,
                COUNT(CASE WHEN r.status <> "rejected" THEN r.id END) AS reg_count
         FROM tbl_events e
         LEFT JOIN tbl_registrations r ON r.event_id = e.id
         WHERE e.id = ?
         GROUP BY e.id
         LIMIT 1'
    );
    if (!$query) {
        error_log("Database prepare error in getEvent: " . $db->error);
        return null;
    }
    $query->bind_param('i', $id);
    $query->execute();
    $result = $query->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}

function createEvent($title, $description, $location, $event_date, $capacity, $image)
{
    global $db;
    $image_path = './assets/images/loading.png';
    if (!empty($image['name'])) {
        $image_path = uploadImage($image);
    }

    $query = $db->prepare(
        'INSERT INTO tbl_events (title, description, location, event_date, capacity, image)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    if (!$query) {
        throw new Exception('DB Error: ' . $db->error);
    }
    $query->bind_param('ssssis', $title, $description, $location, $event_date, $capacity, $image_path);
    $query->execute();

    if ($query->error) {
        throw new Exception('DB Error: ' . $query->error);
    }

    return $db->affected_rows > 0;
}

function updateEvent($id, $title, $description, $location, $event_date, $capacity, $image)
{
    global $db;
    $id = (int)$id;
    $current = getEvent($id);
    if (!$current) {
        return false;
    }

    $image_path = null;
    if (!empty($image['name'])) {
        $image_path = uploadImage($image);
        if ($current->image && file_exists($current->image) && strpos($current->image, 'loading.png') === false) {
            @unlink($current->image);
        }
    }

    if ($image_path) {
        $query = $db->prepare(
            'UPDATE tbl_events SET title=?, description=?, location=?, event_date=?, capacity=?, image=?
             WHERE id=?'
        );
        if (!$query) return false;
        $query->bind_param('ssssisi', $title, $description, $location, $event_date, $capacity, $image_path, $id);
    } else {
        $query = $db->prepare(
            'UPDATE tbl_events SET title=?, description=?, location=?, event_date=?, capacity=?
             WHERE id=?'
        );
        if (!$query) return false;
        $query->bind_param('sssisi', $title, $description, $location, $event_date, $capacity, $id);
    }

    $query->execute();
    return $db->affected_rows >= 0;
}

function deleteEvent($id)
{
    global $db;
    $id = (int)$id;
    $event = getEvent($id);
    if (!$event) {
        return false;
    }

    if ($event->image && file_exists($event->image) && strpos($event->image, 'loading.png') === false) {
        @unlink($event->image);
    }

    $delRegs = $db->prepare('DELETE FROM tbl_registrations WHERE event_id = ?');
    if ($delRegs) {
        $delRegs->bind_param('i', $id);
        $delRegs->execute();
    }

    $query = $db->prepare('DELETE FROM tbl_events WHERE id = ?');
    if (!$query) return false;
    $query->bind_param('i', $id);
    $query->execute();
    return $db->affected_rows > 0;
}

function registerForEvent($event_id, $user_id, $note = '')
{
    global $db;
    $event_id = (int)$event_id;
    $user_id  = (int)$user_id;

    $event = getEvent($event_id);
    if (!$event) {
        throw new Exception('Event not found.');
    }

    if ($event->reg_count >= $event->capacity) {
        throw new Exception('Sorry, this event is already fully booked.');
    }

    if (isUserRegistered($event_id, $user_id)) {
        throw new Exception('You are already registered for this event.');
    }

    $status = 'pending';
    $query = $db->prepare(
        'INSERT INTO tbl_registrations (event_id, user_id, note, status) VALUES (?, ?, ?, ?)'
    );
    if (!$query) {
        throw new Exception('DB Error: ' . $db->error);
    }
    $query->bind_param('iiss', $event_id, $user_id, $note, $status);
    $query->execute();
    return $db->affected_rows > 0;
}

function isUserRegistered($event_id, $user_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT id FROM tbl_registrations WHERE event_id = ? AND user_id = ? LIMIT 1'
    );
    if (!$query) return false;
    $query->bind_param('ii', $event_id, $user_id);
    $query->execute();
    $res = $query->get_result();
    return $res ? ($res->num_rows > 0) : false;
}

function getUserRegistration($event_id, $user_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT * FROM tbl_registrations WHERE event_id = ? AND user_id = ? LIMIT 1'
    );
    if (!$query) return null;
    $query->bind_param('ii', $event_id, $user_id);
    $query->execute();
    $result = $query->get_result();
    if ($result && $result->num_rows > 0) {
        return $result->fetch_object();
    }
    return null;
}

function cancelRegistration($event_id, $user_id)
{
    global $db;
    $query = $db->prepare(
        'DELETE FROM tbl_registrations WHERE event_id = ? AND user_id = ?'
    );
    if (!$query) return false;
    $query->bind_param('ii', $event_id, $user_id);
    $query->execute();
    return $db->affected_rows > 0;
}

function getMyRegistrations($user_id)
{
    global $db;
    $query = $db->prepare(
        'SELECT r.*, e.title, e.location, e.event_date, e.image, e.capacity
         FROM tbl_registrations r
         JOIN tbl_events e ON e.id = r.event_id
         WHERE r.user_id = ?
         ORDER BY e.event_date ASC'
    );
    if (!$query) {
        error_log("Database prepare error in getMyRegistrations: " . $db->error);
        return false;
    }
    $query->bind_param('i', $user_id);
    $query->execute();
    return $query->get_result();
}

function getEventRegistrations($event_id, $status = null)
{
    global $db;
    if ($status !== null && in_array($status, ['pending', 'approved', 'rejected'])) {
        $query = $db->prepare(
            'SELECT r.*, u.name, u.username, u.photo
             FROM tbl_registrations r
             JOIN tbl_users u ON u.id = r.user_id
             WHERE r.event_id = ? AND r.status = ?
             ORDER BY r.created_at ASC'
        );
        if (!$query) return false;
        $query->bind_param('is', $event_id, $status);
    } else {
        $query = $db->prepare(
            'SELECT r.*, u.name, u.username, u.photo
             FROM tbl_registrations r
             JOIN tbl_users u ON u.id = r.user_id
             WHERE r.event_id = ?
             ORDER BY r.created_at ASC'
        );
        if (!$query) return false;
        $query->bind_param('i', $event_id);
    }
    $query->execute();
    return $query->get_result();
}

function updateRegistrationStatus($reg_id, $status)
{
    global $db;
    $allowed = ['pending', 'approved', 'rejected'];
    if (!in_array($status, $allowed)) {
        return false;
    }
    $query = $db->prepare('UPDATE tbl_registrations SET status = ? WHERE id = ?');
    if (!$query) return false;
    $query->bind_param('si', $status, $reg_id);
    $query->execute();
    return $db->affected_rows > 0;
}

function getEventStats()
{
    global $db;
    $stats = new stdClass();

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_events');
    $stats->total_events = ($r && $row = $r->fetch_object()) ? (int)$row->cnt : 0;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_registrations');
    $stats->total_registrations = ($r && $row = $r->fetch_object()) ? (int)$row->cnt : 0;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_registrations WHERE status = "pending"');
    $stats->pending = ($r && $row = $r->fetch_object()) ? (int)$row->cnt : 0;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_registrations WHERE status = "approved"');
    $stats->approved = ($r && $row = $r->fetch_object()) ? (int)$row->cnt : 0;

    $r = $db->query('SELECT COUNT(*) as cnt FROM tbl_users WHERE level <> "admin"');
    $stats->total_users = ($r && $row = $r->fetch_object()) ? (int)$row->cnt : 0;

    return $stats;
}
