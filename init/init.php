<?php
/**
 * Application Initialization
 */

// Enable unconditional output buffering to prevent "headers already sent" errors
ob_start();

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Dynamic base URL detection
$scriptDir = rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? '')), '/');
$baseUrl = ($scriptDir === '' || $scriptDir === '/') ? './' : $scriptDir . '/';
define('BASE_URL', $baseUrl);

// Include database & core helpers
require_once __DIR__ . '/db.init.php';
require_once __DIR__ . '/../includes/icons.inc.php';
require_once __DIR__ . '/../includes/lang.inc.php';
require_once __DIR__ . '/func/auth.func.php';
require_once __DIR__ . '/func/user.func.php';
require_once __DIR__ . '/func/event.func.php';

/**
 * Flash messaging for SweetAlert2 toasts/dialogs
 */
function setFlash($type, $message, $title = '') {
    $_SESSION['flash'] = [
        'type'    => $type, // 'success', 'error', 'warning', 'info'
        'message' => $message,
        'title'   => $title
    ];
}

function getFlash() {
    if (isset($_SESSION['flash'])) {
        $flash = $_SESSION['flash'];
        unset($_SESSION['flash']);
        return $flash;
    }
    return null;
}

function hasFlash() {
    return isset($_SESSION['flash']);
}

/**
 * Safe redirect helper with buffer cleanup and meta/JS fallback
 */
function redirect($url) {
    // Clear any active output buffers so headers can be sent cleanly
    while (ob_get_level() > 0) {
        @ob_end_clean();
    }
    
    if (!headers_sent()) {
        header("Location: " . $url);
    }
    
    // HTML / JS fallback in case headers were already flushed
    echo '<!DOCTYPE html><html><head>';
    echo '<meta http-equiv="refresh" content="0;url=' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">';
    echo '<script>window.location.replace(' . json_encode($url) . ');</script>';
    echo '</head><body>';
    echo '<p>Redirecting to <a href="' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '">' . htmlspecialchars($url, ENT_QUOTES, 'UTF-8') . '</a>...</p>';
    echo '</body></html>';
    exit;
}

/**
 * Safe string escaping helper
 */
function e($value) {
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Helper to build internal URLs
 */
function pageUrl($page = '', $params = []) {
    global $baseUrl;
    if (empty($page)) {
        return $baseUrl;
    }
    $query = ['page' => $page];
    if (!empty($params)) {
        $query = array_merge($query, $params);
    }
    return './?' . http_build_query($query);
}

/**
 * Format datetime helper
 */
function formatEventDate($datetime) {
    return date('M d, Y', strtotime($datetime));
}

function formatEventTime($datetime) {
    return date('h:i A', strtotime($datetime));
}
