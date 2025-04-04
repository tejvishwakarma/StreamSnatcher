<?php

/**
 * StreamSnatcher - Platform Handler
 * Version: 1.0.0
 * Created: 2025-04-04 10:35:46
 * Author: tejvishwakarma
 */

class PlatformHandler
{
    private $url;
    private $isAudioOnly;
    private $platform;
    private $version = '1.0.0';
    private $created = '2025-04-04 10:44:59';
    private $author = 'tejvishwakarma';
    private $defaultUserAgent;
    private $ytDlpPath = null;

    // Define platform configs statically without $this->defaultUserAgent
    private $platformConfigs = [
        'youtube' => [
            'patterns' => ['youtube.com', 'youtu.be'],
            'videoFormat' => '%s+bestaudio[ext=m4a]/bestaudio',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--format-sort quality --throttled-rate 100K --sponsorblock-remove all ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates'
        ],
        'facebook' => [
            'patterns' => ['facebook.com', 'fb.watch', 'fb.gg'],
            'videoFormat' => 'bestvideo+bestaudio/best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'instagram' => [
            'patterns' => ['instagram.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'twitter' => [
            'patterns' => ['twitter.com', 'x.com'],
            'videoFormat' => 'bestvideo+bestaudio/best',
            'audioFormat' => 'bestaudio/best',
            'extraOptions' => '--force-overwrites --concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'tiktok' => [
            'patterns' => ['tiktok.com', 'vm.tiktok.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'vimeo' => [
            'patterns' => ['vimeo.com'],
            'videoFormat' => 'bestvideo+bestaudio/best',
            'audioFormat' => 'bestaudio/best',
            'extraOptions' => '--force-overwrites --concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-warnings'
        ],
        'dailymotion' => [
            'patterns' => ['dailymotion.com', 'dai.ly'],
            'videoFormat' => 'best[height<=720]',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-warnings'
        ],
        'twitch' => [
            'patterns' => ['twitch.tv'],
            'videoFormat' => 'best[height<=1080]',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'pornhub' => [
            'patterns' => ['pornhub.com', 'pornhub.org'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.pornhub.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --extractor-retries 3 --force-generic-extractor --no-playlist ' .
                '--downloader-args "ffmpeg_i:-ss 0 -reconnect 1 -reconnect_streamed 1 -reconnect_delay_max 5" ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.pornhub.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --extractor-retries 3 --force-generic-extractor'
        ],
        'xvideos' => [
            'patterns' => ['xvideos.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.xvideos.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --extractor-retries 3 --force-generic-extractor --no-playlist ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.xvideos.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --extractor-retries 3 --force-generic-extractor'
        ],
        'xhamster' => [
            'patterns' => ['xhamster.com', 'xhamster2.com', 'xhamster.desi', 'xhamster.one'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://xhamster.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://xhamster.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US'
        ],
        'spankbang' => [
            'patterns' => ['spankbang.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://spankbang.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://spankbang.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US'
        ],
        'youporn' => [
            'patterns' => ['youporn.com', 'you-porn.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.youporn.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default --referer "https://www.youporn.com/" ' .
                '--no-warnings --ignore-errors --no-check-certificate --geo-bypass --geo-bypass-country US'
        ],
        'redtube' => [
            'patterns' => ['redtube.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'tube8' => [
            'patterns' => ['tube8.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'hentaihaven' => [
            'patterns' => ['hentaihaven.xxx', 'hentaihaven.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'hanime' => [
            'patterns' => ['hanime.tv'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'eporner' => [
            'patterns' => ['eporner.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'motherless' => [
            'patterns' => ['motherless.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'tnaflix' => [
            'patterns' => ['tnaflix.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'cliphunter' => [
            'patterns' => ['cliphunter.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'hqporner' => [
            'patterns' => ['hqporner.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'onlyfans' => [
            'patterns' => ['onlyfans.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'manyvids' => [
            'patterns' => ['manyvids.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'netflix' => [
            'patterns' => ['netflix.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'primevideo' => [
            'patterns' => ['primevideo.com', 'amazon.com/*/video'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'hotstar' => [
            'patterns' => ['hotstar.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'coursera' => [
            'patterns' => ['coursera.org'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'udemy' => [
            'patterns' => ['udemy.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'reddit' => [
            'patterns' => ['reddit.com', 'redd.it'],
            'videoFormat' => 'bestvideo+bestaudio/best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'imgur' => [
            'patterns' => ['imgur.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'gfycat' => [
            'patterns' => ['gfycat.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'bilibili' => [
            'patterns' => ['bilibili.com', 'b23.tv'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --add-header "Referer: https://www.bilibili.com/" ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates'
        ],
        'niconico' => [
            'patterns' => ['nicovideo.jp', 'nico.ms'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --cookies-from-browser chrome:Default ' .
                '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates --cookies-from-browser chrome:Default'
        ],
        'rutube' => [
            'patterns' => ['rutube.ru'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'streamable' => [
            'patterns' => ['streamable.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'vlive' => [
            'patterns' => ['vlive.tv'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-check-certificates'
        ],
        'arte' => [
            'patterns' => ['arte.tv'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'cnn' => [
            'patterns' => ['cnn.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'espn' => [
            'patterns' => ['espn.com', 'espn.go.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--no-check-certificates --concurrent-fragments 16 --buffer-size 32K --http-chunk-size 10M',
            'infoOptions' => '--no-check-certificates'
        ],
        'soundcloud' => [
            'patterns' => ['soundcloud.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'bandcamp' => [
            'patterns' => ['bandcamp.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ],
        'mixcloud' => [
            'patterns' => ['mixcloud.com'],
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio',
            'extraOptions' => '--concurrent-fragments 16 --buffer-size 32K --http-chunk-size 8M',
            'infoOptions' => '--no-warnings'
        ]
    ];

    public function __construct($url, $isAudioOnly = false)
    {
        $this->url = $url;
        $this->isAudioOnly = $isAudioOnly;
        $this->defaultUserAgent = 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/122.0.0.0 Safari/537.36';

        // Dynamically append User-Agent and headers to specific platforms
        $this->enhancePlatformConfigs();

        // Detect platform
        $this->platform = $this->detectPlatform();

        // Check for required tools
        $this->checkRequirements();

        // Log initialization
        $this->logInfo("PlatformHandler initialized for URL: {$url} by user: tejvishwakarma");
    }

    private function enhancePlatformConfigs()
    {
        $platformsWithHeaders = [
            'facebook',
            'instagram',
            'tiktok',
            'pornhub',
            'xvideos',
            'xhamster',
            'spankbang',
            'youporn',
            'redtube',
            'tube8',
            'hentaihaven',
            'hanime',
            'eporner',
            'motherless',
            'tnaflix',
            'cliphunter',
            'hqporner',
            'onlyfans',
            'manyvids',
            'bilibili',
            'niconico'
        ];

        foreach ($platformsWithHeaders as $platform) {
            if (isset($this->platformConfigs[$platform])) {
                // Append User-Agent to extraOptions
                $this->platformConfigs[$platform]['extraOptions'] .= ' --user-agent "' . $this->defaultUserAgent . '"';

                // Append additional headers where applicable
                if (in_array($platform, ['facebook', 'instagram', 'xhamster', 'spankbang', 'youporn'])) {
                    $this->platformConfigs[$platform]['extraOptions'] .= ' ' .
                        '--add-header "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"';
                }
                if (in_array($platform, ['xhamster', 'spankbang', 'youporn'])) {
                    $this->platformConfigs[$platform]['extraOptions'] .= ' ' .
                        '--add-header "Accept-Language: en-US,en;q=0.5" --add-header "DNT: 1"';
                }

                // Append User-Agent to infoOptions
                if (in_array($platform, [
                    'pornhub',
                    'xvideos',
                    'xhamster',
                    'spankbang',
                    'youporn',
                    'redtube',
                    'tube8',
                    'hentaihaven',
                    'hanime',
                    'eporner',
                    'motherless',
                    'tnaflix',
                    'cliphunter',
                    'hqporner',
                    'onlyfans',
                    'manyvids',
                    'bilibili'
                ])) {
                    $this->platformConfigs[$platform]['infoOptions'] .= ' --user-agent "' . $this->defaultUserAgent . '"';
                }
                if (in_array($platform, ['xhamster', 'spankbang', 'youporn'])) {
                    $this->platformConfigs[$platform]['infoOptions'] .= ' ' .
                        '--add-header "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8" ' .
                        '--add-header "Accept-Language: en-US,en;q=0.5" --add-header "DNT: 1"';
                }
            }
        }
    }

    private function checkRequirements()
    {
        $possiblePaths = [
            'yt-dlp', // Rely on PATH
            '/usr/local/bin/yt-dlp', // Common on macOS/Linux
            '/usr/bin/yt-dlp', // Common on Linux
            'C:\\Tools\\yt-dlp.exe', // Example for Windows, adjust to your install location
            dirname(__DIR__) . '/yt-dlp.exe' // Look in the parent directory (project root)
        ];

        $ytDlpPath = null;
        foreach ($possiblePaths as $path) {
            exec("$path --version 2>&1", $output, $returnVar);
            if ($returnVar === 0) {
                $ytDlpPath = $path;
                $this->logInfo("Found yt-dlp at: $path");
                break;
            }
        }

        if ($ytDlpPath === null) {
            $errorMsg = "yt-dlp not found. Please install yt-dlp first.\n\n";
            // ... error message ...
            $this->logError($errorMsg);
            throw new Exception($errorMsg);
        }

        // Store the found path in the class property
        $this->ytDlpPath = $ytDlpPath;
        $this->logInfo("Using yt-dlp from: $ytDlpPath");

        // Update commands to use $ytDlpPath instead of 'yt-dlp'
        // For now, log it; we'll adjust commands later if needed
        $this->logInfo("Using yt-dlp from: $ytDlpPath");

        exec('which aria2c 2>&1', $output, $returnVar);
        if ($returnVar === 0) {
            $this->logInfo('aria2c found. Using aria2c for faster downloads.');
        } else {
            $this->logWarning('aria2c not found. Downloads may be slower. Consider installing aria2c for better performance.');
        }

        exec('which ffmpeg 2>&1', $output, $returnVar);
        if ($returnVar !== 0) {
            $this->logWarning('ffmpeg not found. Some features like format conversion and merging may not work properly.');
        }
    }

    private function detectPlatform()
    {
        foreach ($this->platformConfigs as $platform => $config) {
            foreach ($config['patterns'] as $pattern) {
                if (strpos($this->url, $pattern) !== false) {
                    $this->logInfo("Detected platform: {$platform}");
                    return $platform;
                }
            }
        }
        $this->logWarning("No specific platform detected for URL: {$this->url}, using generic handler");
        return 'generic';
    }

    public function getFormatString($formatId = null)
    {
        $config = $this->platformConfigs[$this->platform] ?? [
            'videoFormat' => 'best',
            'audioFormat' => 'bestaudio'
        ];

        if ($this->isAudioOnly) {
            return $config['audioFormat'];
        }

        if ($formatId && $this->platform === 'youtube') {
            return sprintf($config['videoFormat'], $formatId);
        }

        return $config['videoFormat'];
    }

    public function getExtraOptions()
    {
        return $this->platformConfigs[$this->platform]['extraOptions'] ?? '--no-check-certificates';
    }

    public function getInfoOptions()
    {
        return $this->platformConfigs[$this->platform]['infoOptions'] ?? '--no-check-certificates';
    }

    public function getDownloadCommand($formatId, $outputTemplate, $resumeDownload = false)
    {
        $formatString = $this->getFormatString($formatId);
        $extraOptions = $this->getExtraOptions();
        $aria2cOptions = '';

        exec('which aria2c', $output, $returnVar);
        if ($returnVar === 0) {
            $aria2cOptions = '--downloader aria2c --external-downloader-args "aria2c:' .
                '--min-split-size=1M ' .
                '--max-connection-per-server=16 ' .
                '--max-concurrent-downloads=16 ' .
                '--split=16"';
        }

        // Add resume option if requested
        $resumeFlag = $resumeDownload ? '--continue --part ' : '';

        $downloadCommand = sprintf(
            '%s -f %s %s %s %s %s -o "%s" --verbose 2>&1',
            $this->ytDlpPath ?: 'yt-dlp',  // Use the stored path if available
            escapeshellarg($formatString),
            escapeshellarg($this->url),
            $extraOptions,
            $aria2cOptions,
            $resumeFlag,
            $outputTemplate
        );

        $this->logInfo("Generated download command: {$downloadCommand}");
        return $downloadCommand;
    }

    public function getLimitedBandwidthCommand($formatId, $outputTemplate, $limitKB = 500)
    {
        $formatString = $this->getFormatString($formatId);
        $extraOptions = $this->getExtraOptions();

        // Add rate limiting
        $rateLimit = "--limit-rate {$limitKB}K";

        $downloadCommand = sprintf(
            '%s -f %s %s %s %s -o "%s" --verbose 2>&1',
            $this->ytDlpPath ?: 'yt-dlp',  // Use the stored path
            escapeshellarg($formatString),
            escapeshellarg($this->url),
            $extraOptions,
            $rateLimit,
            $outputTemplate
        );

        $this->logInfo("Generated bandwidth-limited command: {$downloadCommand}");
        return $downloadCommand;
    }

    public function getAvailableQualities()
    {
        $command = ($this->ytDlpPath ?: 'yt-dlp') . ' --list-formats ' . escapeshellarg($this->url);
        exec($command, $output, $returnVal);

        $qualities = [];
        foreach ($output as $line) {
            if (preg_match('/^(\d+)\s+(\w+)\s+(\d+x\d+|\d+p)\s+/', $line, $matches)) {
                $qualities[] = [
                    'id' => $matches[1],
                    'format' => $matches[2],
                    'resolution' => $matches[3]
                ];
            }
        }

        return $qualities;
    }

    public function getInfoCommand()
    {
        $infoOptions = $this->getInfoOptions();
        $command = sprintf(
            '%s %s --dump-json %s 2>&1',
            $this->ytDlpPath ?: 'yt-dlp',  // Use the stored path
            $infoOptions,
            escapeshellarg($this->url)
        );

        $this->logInfo("Generated info command: {$command}");
        return $command;
    }

    private function getPlatformDownloadSettings()
    {
        $settings = [
            'default' => [
                'concurrent_fragments' => 16,
                'buffer_size' => '32K',
                'http_chunk_size' => '10M',
                'retries' => 5,
                'fragment_retries' => 5
            ],
            'youtube' => [
                'concurrent_fragments' => 16,
                'buffer_size' => '32K',
                'http_chunk_size' => '10M',
                'throttled_rate' => '100K'
            ]
        ];

        return $settings[$this->platform] ?? $settings['default'];
    }

    public function needsAuthentication()
    {
        $authPlatforms = [
            'instagram' => 'https://www.instagram.com/accounts/login/',
            'facebook' => 'https://www.facebook.com/login',
            'twitch' => 'https://www.twitch.tv/login',
            'netflix' => 'https://www.netflix.com/login',
            'primevideo' => 'https://www.amazon.com/ap/signin',
            'hotstar' => 'https://www.hotstar.com/in/login',
            'coursera' => 'https://www.coursera.org/login',
            'udemy' => 'https://www.udemy.com/join/login-popup',
            'niconico' => 'https://account.nicovideo.jp/login',
            'onlyfans' => 'https://onlyfans.com/login',
            'manyvids' => 'https://www.manyvids.com/Login/',
            'pornhub' => 'https://www.pornhub.com/login',
            'xvideos' => 'https://www.xvideos.com/account',
            'xhamster' => 'https://xhamster.com/login',
            'youporn' => 'https://www.youporn.com/login',
            'spankbang' => 'https://spankbang.com/login'
        ];

        if (isset($authPlatforms[$this->platform])) {
            return [
                'required' => true,
                'login_url' => $authPlatforms[$this->platform],
                'platform' => $this->platform,
                'message' => "Please login to {$this->platform} in your Chrome browser first"
            ];
        }

        return ['required' => false];
    }

    public function parseProgress($output)
    {
        $progress = [
            'percent' => 0,
            'speed' => '0 KiB/s',
            'eta' => 'unknown',
            'size' => '0 MiB'
        ];

        if (preg_match('/(\d+\.\d+)% of ~?(\d+\.\d+)(\w+) at\s+(\d+\.\d+)(\w+\/s) ETA (\d+:\d+)/', $output, $matches)) {
            $progress['percent'] = $matches[1];
            $progress['size'] = $matches[2] . $matches[3];
            $progress['speed'] = $matches[4] . $matches[5];
            $progress['eta'] = $matches[6];
        }

        return $progress;
    }

    private function logInfo($message)
    {
        $this->writeLog('INFO', $message);
    }

    private function logWarning($message)
    {
        $this->writeLog('WARNING', $message);
    }

    private function logError($message)
    {
        $this->writeLog('ERROR', $message);
    }

    private function writeLog($level, $message)
    {
        $timestamp = date('Y-m-d H:i:s');
        $user = 'tejvishwakarma';
        $logMessage = "[{$timestamp}] [{$level}] [{$user}] {$message}\n";
        $logFile = __DIR__ . '/logs/platform_handler_' . date('Y-m-d') . '.log';
        $logDir = dirname($logFile);

        if (!is_dir($logDir)) {
            mkdir($logDir, 0755, true);
        }

        error_log($logMessage, 3, $logFile);
    }

    public function getPlatform()
    {
        return $this->platform;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getAuthor()
    {
        return $this->author;
    }
}
