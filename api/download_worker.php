<?php
/**
 * StreamSnatcher - Download Worker
 * Version: 1.0.0
 * Created: 2025-04-03 17:06:24
 * Author: tejvishwakarma
 */

// This script handles the actual download process
session_start();

$downloadId = $argv[1] ?? null;
$url = $argv[2] ?? null;
$formatId = $argv[3] ?? null;

if (!$downloadId || !$url || !$formatId) {
    exit('Missing required parameters');
}

try {
    // Update status to downloading
    $_SESSION['downloads'][$downloadId]['status'] = 'downloading';
    
    // Execute youtube-dl with progress tracking
    $cmd = sprintf(
        'yt-dlp -f %s %s --newline --progress -o "downloads/%%(title)s.%%(ext)s"',
        escapeshellarg($formatId),
        escapeshellarg($url)
    );
    
    $process = popen($cmd, 'r');
    
    while (!feof($process)) {
        $line = fgets($process);
        
        // Parse progress from output
        if (preg_match('/(\d+\.?\d*)%/', $line, $matches)) {
            $progress = floatval($matches[1]);
            $_SESSION['downloads'][$downloadId]['progress'] = $progress;
        }
        
        // Parse filename from output
        if (preg_match('/Destination: downloads\/(.+)$/', $line, $matches)) {
            $_SESSION['downloads'][$downloadId]['filename'] = $matches[1];
        }
    }
    
    $exitCode = pclose($process);
    
    if ($exitCode === 0) {
        $_SESSION['downloads'][$downloadId]['status'] = 'completed';
        $_SESSION['downloads'][$downloadId]['progress'] = 100;
    } else {
        throw new Exception('Download process failed');
    }
    
} catch (Exception $e) {
    $_SESSION['downloads'][$downloadId]['status'] = 'failed';
    $_SESSION['downloads'][$downloadId]['error'] = $e->getMessage();
}