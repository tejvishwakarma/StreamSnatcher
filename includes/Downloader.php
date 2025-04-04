<?php

/**
 * StreamSnatcher - Download Handler
 * Version: 1.0.0
 * Created: 2025-04-03 14:56:48
 * Author: tejvishwakarma
 */

// Prevent direct access
if (!defined('STREAMSNATCHER')) {
    die('Direct access not permitted');
}

class Downloader
{
    private $url;
    private $format;
    private $outputPath;
    private $videoInfo;
    private static $instance = null;
    private $downloadProgress = 0;
    private $lastError = null;
    private $created = '2025-04-03 14:56:48';
    private $author = 'tejvishwakarma';

    /**
     * Private constructor for singleton pattern
     */
    private function __construct()
    {
        $this->checkRequirements();
    }

    /**
     * Get singleton instance
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Check system requirements
     */
    private function checkRequirements()
    {
        // Check if yt-dlp exists
        if (!file_exists(YT_DLP_PATH)) {
            throw new Exception('yt-dlp.exe not found in: ' . YT_DLP_PATH);
        }

        // Check if yt-dlp is executable
        $command = sprintf('"%s" --version 2>&1', YT_DLP_PATH);
        exec($command, $output, $returnVar);

        if ($returnVar !== 0) {
            throw new Exception('yt-dlp.exe is not working properly. Error: ' . implode("\n", $output));
        }

        // Check downloads directory
        if (!is_dir(DOWNLOADS_DIR)) {
            if (!mkdir(DOWNLOADS_DIR, 0755, true)) {
                throw new Exception('Failed to create downloads directory: ' . DOWNLOADS_DIR);
            }
        }

        if (!is_writable(DOWNLOADS_DIR)) {
            throw new Exception('Downloads directory is not writable: ' . DOWNLOADS_DIR);
        }

        // Check temp directory
        if (!is_dir(TEMP_DIR)) {
            if (!mkdir(TEMP_DIR, 0755, true)) {
                throw new Exception('Failed to create temp directory: ' . TEMP_DIR);
            }
        }

        if (!is_writable(TEMP_DIR)) {
            throw new Exception('Temp directory is not writable: ' . TEMP_DIR);
        }
    }

    /**
     * Set URL for download
     */
    public function setUrl($url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            throw new Exception('Invalid URL provided');
        }
        $this->url = escapeshellarg($url);
        $this->videoInfo = null; // Reset video info when URL changes
        return $this;
    }

    /**
     * Get video information
     */
    public function getVideoInfo()
    {
        if (!$this->url) {
            throw new Exception('URL not set');
        }

        try {
            error_log("Fetching video info for URL: " . $this->url);

            $command = sprintf(
                '"%s" --dump-json --no-warnings %s 2>&1',
                YT_DLP_PATH,
                $this->url
            );

            error_log("Executing command: " . $command);

            $output = [];
            $returnVar = 0;
            exec($command, $output, $returnVar);

            if ($returnVar !== 0) {
                error_log("yt-dlp error: " . implode("\n", $output));
                throw new Exception('Failed to get video information: ' . end($output));
            }

            if (empty($output)) {
                throw new Exception('No output from yt-dlp');
            }

            $this->videoInfo = json_decode(implode('', $output), true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Failed to parse video information: ' . json_last_error_msg());
            }

            return [
                'title' => $this->videoInfo['title'] ?? 'Unknown Title',
                'duration' => $this->videoInfo['duration'] ?? 0,
                'thumbnail' => $this->videoInfo['thumbnail'] ?? '',
                'formats' => $this->parseFormats($this->videoInfo['formats'] ?? [])
            ];
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Error in getVideoInfo: " . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Parse available formats
     */
    private function parseFormats($formats)
    {
        $parsed = [];
        foreach ($formats as $format) {
            if (empty($format['format_id'])) {
                continue;
            }

            // Skip formats without filesize if available
            if (isset($format['filesize']) && $format['filesize'] > MAX_FILE_SIZE) {
                continue;
            }

            $ext = strtolower($format['ext'] ?? 'unknown');

            // Skip unsupported formats
            if (!in_array($ext, array_merge(ALLOWED_FORMATS['video'], ALLOWED_FORMATS['audio']))) {
                continue;
            }

            // Determine format type and quality
            $isAudio = isset($format['vcodec']) && $format['vcodec'] === 'none';
            $quality = $this->getQualityLabel($format, $isAudio);
            $filesize = isset($format['filesize']) ? $this->formatFileSize($format['filesize']) : 'Unknown';

            $parsed[] = [
                'format_id' => $format['format_id'],
                'ext' => $ext,
                'quality' => $quality,
                'filesize' => $filesize,
                'type' => $isAudio ? 'audio' : 'video'
            ];
        }

        return $parsed;
    }

    /**
     * Get human-readable quality label
     */
    private function getQualityLabel($format, $isAudio = false)
    {
        if ($isAudio) {
            if (isset($format['abr'])) {
                return round($format['abr']) . 'kbps Audio';
            }
            return 'Audio';
        }

        if (isset($format['height']) && isset($format['fps'])) {
            return "{$format['height']}p{$format['fps']}";
        }

        if (isset($format['height'])) {
            return "{$format['height']}p";
        }

        return isset($format['format_note']) ? $format['format_note'] : 'Unknown';
    }

    /**
     * Format file size to human readable
     */
    private function formatFileSize($bytes)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Download video
     */
    public function download($formatId)
    {
        if (!$this->url || !$formatId) {
            throw new Exception('URL and format ID are required');
        }

        try {
            // Get video info if not already fetched
            if (!$this->videoInfo) {
                $this->getVideoInfo();
            }

            // Generate safe filename with extension
            $extension = $this->getFormatExtension($formatId);
            $filename = $this->generateSafeFilename($this->videoInfo['title']) . '.' . $extension;
            $this->outputPath = DOWNLOADS_DIR . DIRECTORY_SEPARATOR . $filename;

            // Check if this is an audio-only format
            $isAudioOnly = $this->isAudioFormat($formatId);

            // Prepare download command
            if ($isAudioOnly) {
                // For audio-only formats
                $command = sprintf(
                    '"%s" -f %s -o "%s" %s --no-warnings --newline',
                    YT_DLP_PATH,
                    escapeshellarg($formatId),
                    $this->outputPath,
                    $this->url
                );
            } else {
                // For video formats, merge with best audio
                // format ID + best audio with fallback options
                $command = sprintf(
                    '"%s" -f %s+bestaudio/best -o "%s" %s --no-warnings --newline',
                    YT_DLP_PATH,
                    escapeshellarg($formatId),
                    $this->outputPath,
                    $this->url
                );
            }

            error_log("Download command: " . $command);

            // Execute download with progress tracking
            $descriptorSpec = [
                0 => ['pipe', 'r'],  // stdin
                1 => ['pipe', 'w'],  // stdout
                2 => ['pipe', 'w']   // stderr
            ];

            $process = proc_open($command, $descriptorSpec, $pipes);

            if (!is_resource($process)) {
                throw new Exception('Failed to start download process');
            }

            // Set pipes to non-blocking mode
            stream_set_blocking($pipes[1], false);
            stream_set_blocking($pipes[2], false);

            $startTime = time();
            $this->downloadProgress = 0;

            while (true) {
                $status = proc_get_status($process);

                if (!$status['running']) {
                    break;
                }

                // Check timeout
                if (time() - $startTime > DOWNLOAD_TIMEOUT) {
                    proc_terminate($process);
                    throw new Exception('Download timeout exceeded');
                }

                // Read progress from stderr
                $progress = fgets($pipes[2]);
                if ($progress) {
                    $this->updateProgress($progress);
                }

                usleep(100000); // Sleep for 100ms
            }

            // Close pipes
            fclose($pipes[0]);
            fclose($pipes[1]);
            fclose($pipes[2]);

            // Get process exit code
            $exitCode = proc_close($process);

            if ($exitCode !== 0) {
                throw new Exception('Download process failed with exit code: ' . $exitCode);
            }

            // Verify downloaded file
            if (!file_exists($this->outputPath)) {
                throw new Exception('Download file not found');
            }

            $filesize = filesize($this->outputPath);
            if ($filesize === 0) {
                throw new Exception('Downloaded file is empty');
            }

            return [
                'file' => basename($this->outputPath),
                'size' => $filesize,
                'path' => $this->outputPath,
                'created' => date('Y-m-d H:i:s'),
                'author' => $this->author,
                'type' => $isAudioOnly ? 'audio' : 'video'
            ];
        } catch (Exception $e) {
            $this->lastError = $e->getMessage();
            error_log("Download error: " . $e->getMessage());
            $this->cleanup();
            throw $e;
        }
    }

    /**
     * Check if format is audio only
     */
    private function isAudioFormat($formatId)
    {
        if (!$this->videoInfo || !isset($this->videoInfo['formats'])) {
            return false;
        }

        foreach ($this->videoInfo['formats'] as $format) {
            if ($format['format_id'] === $formatId) {
                // Check if it's an audio-only format
                if (isset($format['vcodec']) && $format['vcodec'] === 'none') {
                    return true;
                }
                if (isset($format['acodec']) && !isset($format['vcodec'])) {
                    return true;
                }
                break;
            }
        }

        return false;
    }

    /**
     * Update download progress
     */
    private function updateProgress($progressLine)
    {
        if (preg_match('/(\d+\.?\d*)%/', $progressLine, $matches)) {
            $this->downloadProgress = floatval($matches[1]);
            error_log("Download progress: {$this->downloadProgress}%");
        }
    }

    /**
     * Get format extension
     */
    private function getFormatExtension($formatId)
    {
        if (!$this->videoInfo || !isset($this->videoInfo['formats'])) {
            return 'mp4'; // Default extension
        }

        foreach ($this->videoInfo['formats'] as $format) {
            if ($format['format_id'] === $formatId) {
                return $format['ext'] ?? 'mp4';
            }
        }

        return 'mp4';
    }

    /**
     * Generate safe filename
     */
    private function generateSafeFilename($title)
    {
        // Remove invalid characters
        $filename = preg_replace('/[^a-zA-Z0-9]+/', '-', $title);
        $filename = trim($filename, '-');
        $filename = strtolower($filename);

        // Add timestamp and random string for uniqueness
        $timestamp = date('YmdHis');
        $random = substr(md5(uniqid()), 0, 8);

        return "{$filename}-{$timestamp}-{$random}";
    }

    /**
     * Clean up temporary files
     */
    private function cleanup()
    {
        if ($this->outputPath && file_exists($this->outputPath)) {
            @unlink($this->outputPath);
        }
    }

    /**
     * Get last error
     */
    public function getLastError()
    {
        return $this->lastError;
    }

    /**
     * Get current progress
     */
    public function getProgress()
    {
        return $this->downloadProgress;
    }

    /**
     * Get class info
     */
    public function getInfo()
    {
        return [
            'version' => '1.0.0',
            'created' => $this->created,
            'author' => $this->author,
            'last_error' => $this->lastError,
            'current_progress' => $this->downloadProgress
        ];
    }

    /**
     * Prevent cloning of singleton
     */
    private function __clone() {}

    /**
     * Prevent unserializing of singleton
     */
    public function __wakeup()
    {
        throw new Exception("Cannot unserialize singleton");
    }
}
