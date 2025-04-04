<?php
header('Content-Type: text/plain');

echo "Testing yt-dlp configuration...\n\n";

// Test 1: Check yt-dlp installation
echo "1. Testing yt-dlp installation:\n";
exec('yt-dlp --version', $version, $returnCode);
echo "Version: " . implode("\n", $version) . "\n";
echo "Return code: $returnCode\n\n";

// Test 2: Check Chrome cookies
echo "2. Testing Chrome cookie access:\n";
$cookieFile = '';
if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
    $cookieFile = getenv('LOCALAPPDATA') . '\\Google\\Chrome\\User Data\\Default\\Cookies';
} else {
    $cookieFile = getenv('HOME') . '/.config/google-chrome/Default/Cookies';
}
echo "Cookie file exists: " . (file_exists($cookieFile) ? 'Yes' : 'No') . "\n";
echo "Cookie file path: $cookieFile\n\n";

// Test 3: Test video info fetch
echo "3. Testing video info fetch:\n";
$testUrl = "https://www.youtube.com/watch?v=dQw4w9WgXcQ"; // Safe test URL
$cmd = "yt-dlp --dump-json " . escapeshellarg($testUrl);
exec($cmd, $output, $returnCode);
echo "Command return code: $returnCode\n";
echo "Output available: " . (!empty($output) ? 'Yes' : 'No') . "\n\n";

echo "Test complete.\n";