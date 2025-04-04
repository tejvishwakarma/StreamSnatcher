<?php
/**
 * StreamSnatcher - Video Info Handler
 * Version: 1.0.0
 * Author: tejvishwakarma
 * Created: 2025-04-04 09:17:13
 */

// Disable error display in production
error_reporting(0);
ini_set('display_errors', 0);

require_once 'PlatformHandler.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

// Log configuration
$logFile = __DIR__ . '/logs/streamsnatcher_' . date('Y-m-d') . '.log';
$logDir = dirname($logFile);
if (!is_dir($logDir)) {
    mkdir($logDir, 0755, true);
}

function writeLog($message, $type = 'INFO') {
    global $logFile;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] [$type] $message\n";
    error_log($logMessage, 3, $logFile);
}

function formatSize($bytes) {
    if (!$bytes) return 'Unknown';
    $units = ['B', 'KB', 'MB', 'GB'];
    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);
    $bytes /= pow(1024, $pow);
    return round($bytes, 2) . ' ' . $units[$pow];
}

function validateUrl($url) {
    if (empty($url)) {
        throw new Exception('URL is required');
    }

    if (!filter_var($url, FILTER_VALIDATE_URL)) {
        throw new Exception('Invalid URL format');
    }

    $allowedDomains = [
        'youtube.com', 'youtu.be', 'facebook.com', 'fb.watch', 'instagram.com',
        'twitter.com', 'x.com', 'tiktok.com', 'vm.tiktok.com', 'vimeo.com',
        'dailymotion.com', 'dai.ly', 'twitch.tv', 'pornhub.com', 'pornhub.org',
        'xvideos.com', 'xhamster.com', 'xhamster2.com', 'xhamster.desi',
        'youporn.com', 'you-porn.com', 'spankbang.com', 'redtube.com',
        'xnxx.com', 'bilibili.com'
    ];

    $urlDomain = strtolower(parse_url($url, PHP_URL_HOST));
    $urlDomain = preg_replace('/^www\./', '', $urlDomain);

    $validDomain = false;
    foreach ($allowedDomains as $domain) {
        if (strpos($urlDomain, $domain) !== false) {
            $validDomain = true;
            break;
        }
    }

    if (!$validDomain) {
        throw new Exception('Unsupported website');
    }

    return true;
}

try {
    writeLog("========== Video Info Request Start ==========");
    writeLog("User: tejvishwakarma");

    // Get and validate input
    $rawInput = file_get_contents('php://input');
    if (empty($rawInput)) {
        throw new Exception('No input data provided');
    }

    $input = json_decode($rawInput, true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        throw new Exception('Invalid JSON input: ' . json_last_error_msg());
    }

    if (!isset($input['url'])) {
        throw new Exception('URL is required');
    }

    $url = trim($input['url']);
    validateUrl($url);
    
    writeLog("URL: " . $url);
    
    // Initialize platform handler
    $platformHandler = new PlatformHandler($url);
    $platform = $platformHandler->getPlatform();
    
    writeLog("Platform detected: " . $platform);
    
    // Get video info command
    $cmd = $platformHandler->getInfoCommand();
    writeLog("Command: " . $cmd);

    // Create temporary cookie file if needed
    $cookieFile = null;
    if ($platformHandler->needsAuthentication()) {
        $cookieFile = tempnam(sys_get_temp_dir(), 'yt_cookies_');
        if ($cookieFile === false) {
            throw new Exception('Failed to create temporary cookie file');
        }
        $cmd = str_replace('--cookies-from-browser chrome', '--cookies ' . escapeshellarg($cookieFile), $cmd);
    }

    // Execute command
    $descriptorspec = array(
        1 => array("pipe", "w"),
        2 => array("pipe", "w")
    );
    
    $process = proc_open($cmd, $descriptorspec, $pipes);
    
    if (is_resource($process)) {
        $stdout = stream_get_contents($pipes[1]);
        $stderr = stream_get_contents($pipes[2]);
        fclose($pipes[1]);
        fclose($pipes[2]);
        $returnCode = proc_close($process);

        // Clean up cookie file
        if ($cookieFile && file_exists($cookieFile)) {
            unlink($cookieFile);
        }

        writeLog("Return Code: " . $returnCode);
        if ($stderr) {
            writeLog("Error Output: " . $stderr, 'ERROR');
        }

        if ($returnCode !== 0) {
            // Try fallback options for adult sites
            if (in_array($platform, ['pornhub', 'xvideos', 'xhamster', 'youporn', 'spankbang'])) {
                $fallbackCmd = str_replace('--cookies-from-browser chrome', '', $cmd);
                writeLog("Trying fallback command: " . $fallbackCmd);
                
                exec($fallbackCmd, $fallbackOutput, $fallbackReturnCode);
                
                if ($fallbackReturnCode !== 0) {
                    throw new Exception("Video information fetch failed. Please verify the URL and try again.");
                }
                
                $stdout = implode("\n", $fallbackOutput);
            } else {
                throw new Exception("Failed to fetch video information: " . $stderr);
            }
        }

        $videoInfo = json_decode($stdout, true);
        
        if (!$videoInfo) {
            $jsonError = json_last_error_msg();
            writeLog("JSON decode error: " . $jsonError, 'ERROR');
            writeLog("Raw stdout: " . $stdout, 'ERROR');
            throw new Exception('Invalid video information received');
        }

        // Process formats
        $formats = [];
        if (isset($videoInfo['formats'])) {
            foreach ($videoInfo['formats'] as $format) {
                $formatType = isset($format['vcodec']) && $format['vcodec'] !== 'none' ? 'video' : 'audio';
                $quality = '';
                
                if ($formatType === 'video') {
                    $quality = isset($format['height']) ? $format['height'] . 'p' : 'Unknown';
                    
                    if (isset($format['width']) && isset($format['height'])) {
                        $quality .= ' (' . $format['width'] . 'x' . $format['height'] . ')';
                    }
                    
                    if (isset($format['vbr'])) {
                        $quality .= ' ' . round($format['vbr'], 1) . 'Mbps';
                    }
                } else {
                    $quality = isset($format['abr']) ? $format['abr'] . 'kbps' : 'Unknown';
                }

                if ($formatType === 'video' && (!isset($format['vcodec']) || $format['vcodec'] === 'none')) {
                    continue;
                }

                $formats[] = [
                    'format_id' => $format['format_id'],
                    'ext' => $format['ext'],
                    'filesize' => isset($format['filesize']) ? formatSize($format['filesize']) : 'Unknown',
                    'type' => $formatType,
                    'quality' => $quality,
                    'codec' => isset($format['vcodec']) ? $format['vcodec'] : (isset($format['acodec']) ? $format['acodec'] : 'Unknown')
                ];
            }
        }

        // Filter and sort formats
        $uniqueFormats = [];
        $seen = [];
        foreach ($formats as $format) {
            $key = $format['type'] . $format['quality'];
            if (!isset($seen[$key])) {
                $seen[$key] = true;
                $uniqueFormats[] = $format;
            }
        }

        usort($uniqueFormats, function($a, $b) {
            if ($a['type'] !== $b['type']) {
                return $a['type'] === 'video' ? -1 : 1;
            }
            
            if ($a['type'] === 'video') {
                preg_match('/(\d+)p/', $a['quality'], $aMatch);
                preg_match('/(\d+)p/', $b['quality'], $bMatch);
                $aHeight = isset($aMatch[1]) ? intval($aMatch[1]) : 0;
                $bHeight = isset($bMatch[1]) ? intval($bMatch[1]) : 0;
                return $bHeight - $aHeight;
            } else {
                preg_match('/(\d+)kbps/', $a['quality'], $aMatch);
                preg_match('/(\d+)kbps/', $b['quality'], $bMatch);
                $aBitrate = isset($aMatch[1]) ? intval($aMatch[1]) : 0;
                $bBitrate = isset($bMatch[1]) ? intval($bMatch[1]) : 0;
                return $bBitrate - $aBitrate;
            }
        });

        $response = [
            'success' => true,
            'data' => [
                'title' => $videoInfo['title'] ?? 'Unknown Title',
                'thumbnail' => $videoInfo['thumbnail'] ?? null,
                'duration' => $videoInfo['duration'] ?? 0,
                'description' => $videoInfo['description'] ?? '',
                'uploader' => $videoInfo['uploader'] ?? 'Unknown',
                'view_count' => $videoInfo['view_count'] ?? 0,
                'formats' => $uniqueFormats
            ],
            'timestamp' => date('Y-m-d H:i:s')
        ];

        writeLog("Sending successful response");
        echo json_encode($response);

    } else {
        throw new Exception('Failed to execute command');
    }

} catch (Exception $e) {
    writeLog("Error occurred: " . $e->getMessage(), 'ERROR');
    
    $errorResponse = [
        'success' => false,
        'error' => $e->getMessage(),
        'debug' => [
            'timestamp' => date('Y-m-d H:i:s'),
            'platform' => isset($platformHandler) ? $platformHandler->getPlatform() : 'unknown',
            'command' => isset($cmd) ? $cmd : 'not executed',
            'returnCode' => isset($returnCode) ? $returnCode : 'unknown',
            'stderr' => isset($stderr) ? $stderr : 'not captured'
        ]
    ];
    
    echo json_encode($errorResponse);
}

writeLog("========== Video Info Request End ==========\n");