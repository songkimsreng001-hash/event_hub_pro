<?php
/**
 * Application Front Controller & Router
 */

require_once __DIR__ . '/init/init.php';

$user = loggedInUser();
$isAdmin = isAdmin();

// Route definitions
$admin_pages = [
    'user/list',
    'user/create',
    'user/update',
    'user/delete',
    'event/create',
    'event/create_event', // alias
    'event/edit',
    'event/delete',
    'event/delete_event', // alias
    'event/registrations'
];

$logged_in_pages = [
    'dashboard',
    'profile',
    'event/register',
    'event/cancel',
    'my-registrations'
];

$non_logged_in_pages = [
    'login',
    'register'
];

$public_pages = [
    'events',
    'event/detail'
];

$available_pages = array_merge(
    ['logout'],
    $non_logged_in_pages,
    $logged_in_pages,
    $admin_pages,
    $public_pages
);

$page = $_GET['page'] ?? '';
if (empty($page) && isset($_SERVER['REQUEST_URI'])) {
    $uriPath = trim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH) ?? '', '/');
    if (!empty($uriPath) && !str_starts_with($uriPath, 'assets') && !str_starts_with($uriPath, 'api') && $uriPath !== 'index.php') {
        $page = $uriPath;
    }
}

// Support URL aliases for clean routes and backwards compatibility
$aliases = [
    'event/create_event' => 'event/create',
    'event/delete_event' => 'event/delete',
];

if (isset($aliases[$page])) {
    $page = $aliases[$page];
}

// Redirect guests attempting to access protected pages
if (in_array($page, $logged_in_pages) && empty($user)) {
    setFlash('warning', 'Please sign in to access that page.', 'Authentication Required');
    redirect('./?page=login');
}

// Redirect logged-in users attempting to access guest pages
if (in_array($page, $non_logged_in_pages) && !empty($user)) {
    redirect('./?page=dashboard');
}

// Enforce admin permissions
if (in_array($page, $admin_pages) && !$isAdmin) {
    setFlash('error', 'You do not have administrative privileges to access that section.', 'Access Denied');
    redirect('./?page=dashboard');
}

// Resolve page script
$pageFile = null;
if (!empty($page) && in_array($page, $available_pages)) {
    $candidate = __DIR__ . '/pages/' . $page . '.php';
    if (file_exists($candidate)) {
        $pageFile = $candidate;
    }
}

// Fallback if no page or route was not matched
if (!$pageFile) {
    if ($user) {
        $page = 'dashboard';
        $pageFile = __DIR__ . '/pages/dashboard.php';
    } else {
        $page = 'events';
        $pageFile = __DIR__ . '/pages/events.php';
    }
}

// Action-only routes that perform processing and redirect immediately
$action_pages = [
    'logout',
    'event/cancel',
    'event/delete',
    'event/delete_event',
    'user/delete'
];

if (in_array($page, $action_pages)) {
    include $pageFile;
    exit;
}

// Execute and capture page content before rendering layout
// If the page processes a form and redirects, it exits cleanly without sending any HTML!
ob_start();
include $pageFile;
$pageContent = ob_get_clean();

// Render Layout & Active Page Content
include __DIR__ . '/includes/header.inc.php';

if ($isAdmin) {
    include __DIR__ . '/includes/sidebar.inc.php';
    echo '<div id="adminMainWrapper" class="lg:pl-64 flex flex-col min-h-screen transition-all duration-300 ease-in-out">';
    include __DIR__ . '/includes/navbar.inc.php';
    echo '<main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">';
    echo $pageContent;
    echo '</main>';
    include __DIR__ . '/includes/footer.inc.php';
    echo '</div>';
} else {
    include __DIR__ . '/includes/navbar.inc.php';
    echo '<main class="flex-grow w-full max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">';
    echo $pageContent;
    echo '</main>';
    include __DIR__ . '/includes/footer.inc.php';
}
