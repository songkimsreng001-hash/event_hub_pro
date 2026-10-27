<?php
/**
 * Database Initialization & Environment Configuration
 * Supports Local XAMPP and Cloud / Vercel Serverless Environments
 */

// Default local configuration
$db_host = '127.0.0.1';
$db_name = 'event_mgt';
$db_user = 'root';
$db_pass = '';
$db_port = 3306;

// 1. Check for single Connection String (e.g. TiDB, Railway, PlanetScale, Aiven)
$connUrl = getenv('DATABASE_URL') ?: (getenv('MYSQL_URL') ?: ($_ENV['DATABASE_URL'] ?? ($_ENV['MYSQL_URL'] ?? null)));
if ($connUrl) {
    $parsed = parse_url($connUrl);
    if ($parsed) {
        $db_host = $parsed['host'] ?? $db_host;
        $db_port = (int)($parsed['port'] ?? $db_port);
        $db_user = $parsed['user'] ?? $db_user;
        $db_pass = $parsed['pass'] ?? $db_pass;
        $db_name = ltrim($parsed['path'] ?? $db_name, '/');
    }
}

// 2. Read specific Environment Variables (if set, overrides URL or defaults)
$db_host = getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: ($_ENV['DB_HOST'] ?? ($_ENV['MYSQLHOST'] ?? $db_host)));
$db_name = getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: ($_ENV['DB_NAME'] ?? ($_ENV['MYSQLDATABASE'] ?? $db_name)));
$db_user = getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: ($_ENV['DB_USER'] ?? ($_ENV['MYSQLUSER'] ?? $db_user)));
$envPass = getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('DB_PASSWORD') !== false ? getenv('DB_PASSWORD') : (getenv('MYSQLPASSWORD') !== false ? getenv('MYSQLPASSWORD') : null));
if ($envPass !== null) {
    $db_pass = $envPass;
} elseif (isset($_ENV['DB_PASS'])) {
    $db_pass = $_ENV['DB_PASS'];
}
$db_port = (int)(getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: ($_ENV['DB_PORT'] ?? ($_ENV['MYSQLPORT'] ?? $db_port))));

// 3. SSL Configuration (Auto-enabled for remote cloud hosts or when DB_SSL is set)
$isCloudHost = ($db_host !== '127.0.0.1' && $db_host !== 'localhost');
$useSsl = (getenv('DB_SSL') === 'true' || getenv('MYSQL_SSL') === 'true' || strpos($db_host, 'tidbcloud.com') !== false || strpos($db_host, 'aivencloud.com') !== false);

mysqli_report(MYSQLI_REPORT_OFF);

if ($useSsl) {
    $db = mysqli_init();
    $sslCa = getenv('MYSQL_ATTR_SSL_CA') ?: (getenv('DB_SSL_CA') ?: null);
    if ($sslCa && file_exists($sslCa)) {
        $db->ssl_set(NULL, NULL, $sslCa, NULL, NULL);
    } else {
        $db->ssl_set(NULL, NULL, NULL, NULL, NULL);
    }
    @$db->real_connect($db_host, $db_user, $db_pass, $db_name, $db_port, NULL, MYSQLI_CLIENT_SSL);
} else {
    $db = @new mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
}

// 4. Connection Error Handling with Context-Aware Guidance
if ($db->connect_error) {
    if (php_sapi_name() === 'cli') {
        echo "Database connection error: " . $db->connect_error . "\n";
        return;
    }
    
    $isVercel = (getenv('VERCEL') === '1' || isset($_SERVER['VERCEL']));
    ?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Database Connection Notice - EventHub Pro</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-slate-50 min-h-screen flex items-center justify-center p-4 font-sans">
        <div class="max-w-lg w-full bg-white rounded-3xl shadow-xl p-8 border border-slate-100 text-center">
            <div class="w-16 h-16 bg-amber-100 text-amber-600 rounded-2xl flex items-center justify-center mx-auto mb-4 shadow-sm">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            </div>
            <h2 class="text-xl font-extrabold text-slate-800 mb-2">Database Connection Required</h2>
            <p class="text-slate-600 text-sm mb-4">
                Could not connect to database <strong class="text-slate-900"><?= htmlspecialchars($db_name) ?></strong> on <strong class="text-slate-900"><?= htmlspecialchars($db_host) ?>:<?= (int)$db_port ?></strong>.
            </p>
            <div class="bg-slate-50 p-3.5 rounded-xl text-xs text-left text-red-600 font-mono mb-5 border border-slate-200 break-words">
                <?= htmlspecialchars($db->connect_error) ?>
            </div>

            <?php if ($isVercel): ?>
                <div class="text-xs text-slate-600 mb-6 text-left space-y-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="font-bold text-slate-800">Vercel Deployment Setup:</p>
                    <p>1. Go to your <strong class="text-slate-800">Vercel Dashboard &rarr; Project Settings &rarr; Environment Variables</strong>.</p>
                    <p>2. Add your MySQL connection credentials:</p>
                    <ul class="list-disc pl-5 space-y-1 font-mono text-[11px] text-slate-700">
                        <li><code>DB_HOST</code> (e.g. your TiDB, Aiven, or Railway host)</li>
                        <li><code>DB_NAME</code>, <code>DB_USER</code>, <code>DB_PASS</code>, <code>DB_PORT</code></li>
                        <li>Or simply set <code>DATABASE_URL</code></li>
                    </ul>
                    <p>3. Import <code class="text-emerald-700">database.sql</code> into your cloud MySQL database.</p>
                </div>
            <?php else: ?>
                <div class="text-xs text-slate-500 mb-6 text-left space-y-2 bg-slate-50 p-4 rounded-xl border border-slate-100">
                    <p class="font-bold text-slate-700">Local Setup Instructions (XAMPP):</p>
                    <p>1. Start <strong class="text-slate-700">Apache</strong> and <strong class="text-slate-700">MySQL</strong> in the XAMPP Control Panel.</p>
                    <p>2. Open <strong class="text-slate-700">phpMyAdmin</strong> and create database <strong class="text-slate-700">event_mgt</strong>.</p>
                    <p>3. Import the file <strong class="text-slate-700">database.sql</strong> from this project root.</p>
                </div>
            <?php endif; ?>

            <button onclick="window.location.reload()" class="w-full py-3 px-4 bg-emerald-600 hover:bg-emerald-700 text-white font-bold rounded-xl transition shadow-md shadow-emerald-600/20">
                Retry Connection
            </button>
        </div>
    </body>
    </html>
    <?php
    exit;
}

$db->set_charset("utf8mb4");

// Quiet schema compatibility migration: ensure photo & image columns can hold Base64 URIs
@$db->query("ALTER TABLE tbl_users MODIFY photo MEDIUMTEXT");
@$db->query("ALTER TABLE tbl_events MODIFY image MEDIUMTEXT");
