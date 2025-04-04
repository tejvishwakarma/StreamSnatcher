<?php
/**
 * StreamSnatcher - Configuration File
 * Version: 1.0.0
 * Created: 2025-04-03 14:50:23
 * Author: tejvishwakarma
 */

// Prevent direct access
if (!defined('STREAMSNATCHER')) {
    die('Direct access not permitted');
}

// Error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Application settings
define('APP_NAME', 'StreamSnatcher');
define('APP_VERSION', '1.0.0');
define('APP_AUTHOR', 'tejvishwakarma');
define('APP_CREATED', '2025-04-03 14:50:23');

// Directory paths - Define these first
define('ROOT_DIR', dirname(__DIR__));
define('INCLUDES_DIR', ROOT_DIR . '/includes');
define('DOWNLOADS_DIR', ROOT_DIR . '/downloads');
define('TEMP_DIR', ROOT_DIR . '/temp');

// yt-dlp configuration
define('YT_DLP_PATH', dirname(__DIR__) . '/yt-dlp.exe'); // For Windows
define('MAX_DOWNLOADS', 3); // Maximum concurrent downloads
define('DOWNLOAD_TIMEOUT', 3600); // Maximum download time (1 hour)

// Allowed video formats
define('ALLOWED_FORMATS', [
    'video' => ['mp4', 'webm', 'mkv'],
    'audio' => ['mp3', 'm4a', 'wav']
]);

// File size limits (in bytes)
define('MAX_FILE_SIZE', 2147483648); // 2GB

// API rate limiting
define('RATE_LIMIT', [
    'window' => 3600,    // 1 hour
    'max_requests' => 100 // Maximum requests per window
]);

// Directory creation and permissions check
$required_dirs = [DOWNLOADS_DIR, TEMP_DIR];
foreach ($required_dirs as $dir) {
    if (!file_exists($dir)) {
        if (!@mkdir($dir, 0755, true)) {
            die("Error: Unable to create directory '$dir'");
        }
    }
    if (!is_writable($dir)) {
        die("Error: Directory '$dir' is not writable");
    }
}

// Check if yt-dlp is installed
function checkYtDlp() {
    if (!file_exists(YT_DLP_PATH)) {
        die('Error: yt-dlp.exe not found. Please place yt-dlp.exe in the root directory of the project.');
    }

    $command = sprintf('"%s" --version 2>&1', YT_DLP_PATH);
    exec($command, $output, $returnVar);
    
    if ($returnVar !== 0) {
        die('Error: yt-dlp.exe is not working properly. Please ensure you have a valid yt-dlp.exe file.');
    }
    
    // Log yt-dlp version
    error_log('yt-dlp version: ' . implode('', $output));
}

// Check yt-dlp on startup
checkYtDlp();

// CORS settings
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Security headers
header('X-Content-Type-Options: nosniff');
header('X-Frame-Options: DENY');
header('X-XSS-Protection: 1; mode=block');

// Initialize error handler
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    $error_message = sprintf(
        "Error [%d]: %s\nFile: %s\nLine: %d",
        $errno,
        $errstr,
        $errfile,
        $errline
    );
    
    error_log($error_message);
    
    // Don't output anything if headers have been sent
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => 'An internal error occurred'
        ]);
    }
    
    exit;
});

// Initialize exception handler
set_exception_handler(function($exception) {
    $error_message = sprintf(
        "Exception: %s\nFile: %s\nLine: %d\nTrace:\n%s",
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
    
    error_log($error_message);
    
    // Don't output anything if headers have been sent
    if (!headers_sent()) {
        http_response_code(500);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'success' => false,
            'error' => 'An unexpected error occurred'
        ]);
    }
    
    exit;
});

// Load required files - Do this last
require_once INCLUDES_DIR . '/Downloader.php';