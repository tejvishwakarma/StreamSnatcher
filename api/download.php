<?php
/**
 * StreamSnatcher - Download Handler
 * Version: 1.0.0
 * Created: 2025-04-04 06:39:26
 * Author: tejvishwakarma
 */

// Set unlimited execution time
set_time_limit(0);
ini_set('memory_limit', '1024M');

function sendJsonResponse($success, $message, $data = null) {
    header('Content-Type: application/json');
    echo json_encode([
        'success' => $success,
        'error' => $success ? null : $message,
        'data' => $data,
        'timestamp' => date('Y-m-d H:i:s')
    ]);
    exit;
}

function getPlatform($url) {
    if (strpos($url, 'youtube.com') !== false || strpos($url, 'youtu.be') !== false) {
        return 'youtube';
    } elseif (strpos($url, 'vimeo.com') !== false) {
        return 'vimeo';
    } elseif (strpos($url, 'twitter.com') !== false || strpos($url, 'x.com') !== false) {
        return 'twitter';
    } elseif (strpos($url, 'instagram.com') !== false) {
        return 'instagram';
    } elseif (strpos($url, 'twitch.tv') !== false) {
        return 'twitch';
    } elseif (strpos($url, 'dailymotion.com') !== false) {
        return 'dailymotion';
    } elseif (strpos($url, 'facebook.com') !== false || strpos($url, 'fb.watch') !== false) {
        return 'facebook';
    } elseif (strpos($url, 'reddit.com') !== false) {
        return 'reddit';
    }
    return 'unknown';
}

try {
    // Get and validate input
    $input = json_decode(file_get_contents('php://input'), true);
    
    if (!$input || !isset($input['url']) || !isset($input['formatId'])) {
        throw new Exception('Missing required parameters');
    }

    $url = $input['url'];
    $formatId = $input['formatId'];
    $title = $input['title'] ?? 'video';
    $isAudioOnly = isset($input['isAudioOnly']) && $input['isAudioOnly'] === true;
    $platform = getPlatform($url);

    // Sanitize title for filename
    $title = preg_replace('/[^a-z0-9]/i', '_', $title);
    
    // Ensure downloads directory exists
    $downloadsDir = __DIR__ . '/../downloads';
    if (!is_dir($downloadsDir)) {
        if (!mkdir($downloadsDir, 0755, true)) {
            throw new Exception('Failed to create downloads directory');
        }
    }

    if (!is_writable($downloadsDir)) {
        throw new Exception('Downloads directory is not writable');
    }

    chdir($downloadsDir);

    // Platform-specific format handling
    $formatString = '';
    $extraOptions = '';

    switch ($platform) {
        case 'vimeo':
        case 'twitter':
            if (!$isAudioOnly) {
                $formatString = 'bestvideo+bestaudio/best';
                $extraOptions = '--force-overwrites';
            } else {
                $formatString = 'bestaudio/best';
            }
            break;

        case 'instagram':
            $extraOptions = '--no-check-certificates --cookies-from-browser chrome';
            $formatString = $isAudioOnly ? 'bestaudio' : 'best';
            break;

        case 'twitch':
            $extraOptions = '--no-check-certificates --cookies-from-browser chrome';
            $formatString = $isAudioOnly ? 'bestaudio' : 'best[height<=1080]';
            break;

        case 'dailymotion':
            // First, get available formats
            $listFormatsCmd = sprintf(
                'yt-dlp -F %s',
                escapeshellarg($url)
            );
            exec($listFormatsCmd, $formatOutput, $formatReturnCode);
            
            if ($isAudioOnly) {
                $formatString = 'bestaudio';
            } else {
                // Parse formats and select appropriate one
                $formatString = 'best[height<=720]'; // Default to 720p
            }
            break;

        default:
            if (!$isAudioOnly) {
                $formatString = $formatId . '+bestaudio[ext=m4a]/bestaudio';
            } else {
                $formatString = $formatId;
            }
    }

    // Prepare the download command
    $cmd = sprintf(
        'yt-dlp -f %s %s --merge-output-format mp4 %s -o "%s" --verbose 2>&1',
        escapeshellarg($formatString),
        escapeshellarg($url),
        $extraOptions,
        $title . '.%(ext)s'
    );

    // Execute command
    exec($cmd, $output, $returnCode);

    // Debug information
    error_log("Platform detected: $platform");
    error_log("Command executed: $cmd");
    error_log("Return code: $returnCode");
    error_log("Output: " . print_r($output, true));

    if ($returnCode !== 0) {
        throw new Exception('Download failed: ' . implode("\n", $output));
    }

    // Find downloaded file
    $files = glob($downloadsDir . '/' . $title . '.*');
    
    if (empty($files)) {
        throw new Exception('Could not locate downloaded file');
    }

    $downloadedFile = $files[0];
    
    if (!file_exists($downloadedFile)) {
        throw new Exception('Downloaded file does not exist');
    }

    $filesize = filesize($downloadedFile);
    if ($filesize === 0) {
        throw new Exception('Downloaded file is empty');
    }

    // Set content type
    $extension = strtolower(pathinfo($downloadedFile, PATHINFO_EXTENSION));
    $contentType = 'application/octet-stream';
    
    $mimeTypes = [
        'mp4' => 'video/mp4',
        'webm' => 'video/webm',
        'mp3' => 'audio/mpeg',
        'm4a' => 'audio/mp4',
        'wav' => 'audio/wav',
        'ogg' => 'audio/ogg'
    ];
    
    if (isset($mimeTypes[$extension])) {
        $contentType = $mimeTypes[$extension];
    }

    // Send headers
    header('Content-Type: ' . $contentType);
    header('Content-Disposition: attachment; filename="' . basename($downloadedFile) . '"');
    header('Content-Length: ' . $filesize);
    header('Cache-Control: no-cache, must-revalidate');
    header('Pragma: no-cache');
    header('Access-Control-Allow-Origin: *');
    
    // Send file
    if (!readfile($downloadedFile)) {
        throw new Exception('Failed to send file');
    }
    
    // Cleanup
    unlink($downloadedFile);

} catch (Exception $e) {
    error_log("Download error: " . $e->getMessage());
    sendJsonResponse(false, $e->getMessage());
}