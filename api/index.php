<?php
/**
 * Vercel Serverless PHP Function Entrypoint
 * Forwards requests to root index.php while establishing serverless context
 */

// Establish project root as current working directory
chdir(dirname(__DIR__));

// Ensure session directory is writable in serverless Lambda environment
if (session_status() === PHP_SESSION_NONE) {
    $tempDir = sys_get_temp_dir();
    if (is_dir($tempDir) && is_writable($tempDir)) {
        session_save_path($tempDir);
    }
}

// Forward to application front controller
require __DIR__ . '/../index.php';

