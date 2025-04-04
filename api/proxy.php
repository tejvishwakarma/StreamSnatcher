<?php
/**
 * StreamSnatcher - Image Proxy
 * Version: 1.0.0
 * Created: 2025-04-04 09:51:32
 * Author: tejvishwakarma
 */

// Error handling
error_reporting(0);
ini_set('display_errors', 0);

// Allow images to be cached by browsers
header('Cache-Control: public, max-age=86400');

// Image Cache Class
class ImageCache {
    private $cacheDir;
    private $maxAge;
    private $maxSize;

    public function __construct($maxAge = 86400, $maxSize = 10485760) { // 24 hours, 10MB
        $this->cacheDir = __DIR__ . '/cache/images/';
        $this->maxAge = $maxAge;
        $this->maxSize = $maxSize;
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }

        // Cleanup old cache files periodically (1% chance)
        if (mt_rand(1, 100) === 1) {
            $this->cleanup();
        }
    }

    public function getCacheKey($url) {
        return md5($url);
    }

    public function getCachePath($url) {
        return $this->cacheDir . $this->getCacheKey($url);
    }

    public function get($url) {
        $cachePath = $this->getCachePath($url);
        
        if (file_exists($cachePath)) {
            $age = time() - filemtime($cachePath);
            if ($age < $this->maxAge) {
                return [
                    'data' => file_get_contents($cachePath),
                    'content_type' => $this->getContentType($cachePath)
                ];
            }
            unlink($cachePath); // Remove expired cache
        }
        
        return false;
    }

    public function set($url, $data, $contentType) {
        $cachePath = $this->getCachePath($url);
        if (strlen($data) <= $this->maxSize) {
            file_put_contents($cachePath, $data);
            file_put_contents($cachePath . '.meta', $contentType);
            return true;
        }
        return false;
    }

    private function getContentType($cachePath) {
        $metaPath = $cachePath . '.meta';
        return file_exists($metaPath) ? file_get_contents($metaPath) : 'image/jpeg';
    }

    private function cleanup() {
        $files = glob($this->cacheDir . '*');
        foreach ($files as $file) {
            if (is_file($file)) {
                $age = time() - filemtime($file);
                if ($age > $this->maxAge) {
                    unlink($file);
                    if (file_exists($file . '.meta')) {
                        unlink($file . '.meta');
                    }
                }
            }
        }
    }
}

// Logger Class
class Logger {
    private $logFile;

    public function __construct() {
        $this->logFile = __DIR__ . '/logs/proxy_' . date('Y-m-d') . '.log';
        $logDir = dirname($this->logFile);
        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }
    }

    public function log($message, $type = 'INFO') {
        $timestamp = date('Y-m-d H:i:s');
        $logMessage = "[$timestamp] [$type] $message\n";
        error_log($logMessage, 3, $this->logFile);
    }
}

// Initialize logger
$logger = new Logger();
$logger->log("=== New Request Started ===");
$logger->log("User: tejvishwakarma");

try {
    // Validate URL parameter
    if (!isset($_GET['url'])) {
        throw new Exception('URL parameter is required');
    }

    $imageUrl = $_GET['url'];
    $logger->log("Requested URL: $imageUrl");

    // Validate URL
    if (!filter_var($imageUrl, FILTER_VALIDATE_URL)) {
        throw new Exception('Invalid URL format');
    }

    // Whitelist of allowed domains
    $allowedDomains = [
        'hdslb.com',
        'bilibili.com',
        'youtube.com',
        'ytimg.com',
        'twimg.com',
        'fbcdn.net',
        'instagram.com',
        'cdninstagram.com',
        'tiktokcdn.com',
        'vimeocdn.com',
        'dailymotion.com',
        'dmcdn.net',
        'pornhub.com',
        'phncdn.com',
        'xvideos.com',
        'xhcdn.com',
        'redtube.com',
        'rdtcdn.com'
    ];

    $urlDomain = strtolower(parse_url($imageUrl, PHP_URL_HOST));
    $isAllowed = false;
    foreach ($allowedDomains as $domain) {
        if (strpos($urlDomain, $domain) !== false) {
            $isAllowed = true;
            break;
        }
    }

    if (!$isAllowed) {
        throw new Exception('Domain not allowed: ' . $urlDomain);
    }

    // Initialize cache
    $cache = new ImageCache();
    
    // Try to get from cache first
    $cachedImage = $cache->get($imageUrl);
    if ($cachedImage !== false) {
        $logger->log("Cache HIT for URL: $imageUrl");
        header('Content-Type: ' . $cachedImage['content_type']);
        header('X-Cache: HIT');
        echo $cachedImage['data'];
        exit;
    }

    $logger->log("Cache MISS for URL: $imageUrl");

    // Initialize cURL
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => $imageUrl,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS => 5,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36',
        CURLOPT_ENCODING => '',
        CURLOPT_HTTPHEADER => [
            'Accept: image/webp,image/apng,image/*,*/*;q=0.8',
            'Accept-Language: en-US,en;q=0.9',
            'Referer: ' . parse_url($imageUrl, PHP_URL_SCHEME) . '://' . $urlDomain
        ]
    ]);

    // Get image data
    $imageData = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $contentType = curl_getinfo($ch, CURLINFO_CONTENT_TYPE);

    if ($httpCode !== 200) {
        throw new Exception('Failed to fetch image: HTTP ' . $httpCode);
    }

    if (!$imageData) {
        throw new Exception('Failed to fetch image: ' . curl_error($ch));
    }

    curl_close($ch);

    // Validate content type
    if (!preg_match('/^image\//i', $contentType)) {
        throw new Exception('Invalid content type: ' . $contentType);
    }

    // Cache the image
    $cache->set($imageUrl, $imageData, $contentType);
    
    // Set headers
    header('Content-Type: ' . $contentType);
    header('X-Cache: MISS');
    
    // Output image data
    echo $imageData;

    $logger->log("Successfully proxied and cached image: $imageUrl");

} catch (Exception $e) {
    $logger->log("Error: " . $e->getMessage(), 'ERROR');
    
    // Return a placeholder image
    header('Content-Type: image/svg+xml');
    echo '<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100">
            <rect width="100" height="100" fill="#eee"/>
            <text x="50" y="50" font-family="Arial" font-size="12" fill="#999" text-anchor="middle">Image Error</text>
          </svg>';
}

$logger->log("=== Request Completed ===\n");