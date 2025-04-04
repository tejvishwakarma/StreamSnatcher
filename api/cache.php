<?php
class ImageCache {
    private $cacheDir;
    private $maxAge; // Cache duration in seconds

    public function __construct($maxAge = 86400) { // 24 hours default
        $this->cacheDir = __DIR__ . '/cache/images/';
        $this->maxAge = $maxAge;
        
        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0755, true);
        }
    }

    public function getCacheKey($url) {
        return md5($url);
    }

    public function get($url) {
        $cacheKey = $this->getCacheKey($url);
        $cachePath = $this->cacheDir . $cacheKey;
        
        if (file_exists($cachePath)) {
            $age = time() - filemtime($cachePath);
            if ($age < $this->maxAge) {
                return file_get_contents($cachePath);
            }
            unlink($cachePath); // Remove expired cache
        }
        
        return false;
    }

    public function set($url, $data) {
        $cacheKey = $this->getCacheKey($url);
        $cachePath = $this->cacheDir . $cacheKey;
        file_put_contents($cachePath, $data);
    }
}