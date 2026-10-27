<?php
$db_host = '127.0.0.1';
$db_name = 'event_mgt';
$db_user = 'root';
$db_pass = '';
$db_port = 3306;

mysqli_report(MYSQLI_REPORT_OFF);
$db = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);

if ($db->connect_error) {
    if (php_sapi_name() === 'cli') {
        echo "Database connection error: " . $db->connect_error . "\n";
        return;
    }
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Connection Notice - Event Management</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">
        <div class="max-w-md w-full bg-white rounded-2xl shadow-xl p-8 border border-slate-100 text-center">
            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h2 class="text-xl font-bold text-slate-800 mb-2">Database Connection Required</h2>
            <p class="text-slate-600 text-sm mb-4">
                Could not connect to database <strong class="text-slate-900">event_mgt</strong> on <strong class="text-slate-900">127.0.0.1:3306</strong>.
            </p>
            <div class="bg-slate-50 p-3 rounded-lg text-xs text-left text-slate-600 font-mono mb-4 border border-slate-200">
                <?= htmlspecialchars($db->connect_error) ?>
            </div>
            <div class="text-xs text-slate-500 mb-6 text-left space-y-2 bg-slate-50 p-3 rounded-lg">
                <p class="font-semibold text-slate-700">Quick Setup Instructions:</p>
                <p>1. Start <strong class="text-slate-700">Apache</strong> and <strong class="text-slate-700">MySQL</strong> in XAMPP.</p>
                <p>2. Open <strong class="text-slate-700">phpMyAdmin</strong> and create database <strong class="text-slate-700">event_mgt</strong>.</p>
                <p>3. Import the file <strong class="text-slate-700">database.sql</strong> from this project root.</p>
            </div>
            <button onclick="window.location.reload()" class="w-full py-2.5 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-medium rounded-xl transition shadow">
                Retry Connection
            </button>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$db->set_charset("utf8mb4");
