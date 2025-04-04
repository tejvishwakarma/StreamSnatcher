<?php
// Create directories if they don't exist
@mkdir('../assets/fonts', 0755, true);
@mkdir('../assets/css', 0755, true);

// Download Font Awesome CSS
$fontAwesomeCss = file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css');
file_put_contents('../assets/css/fontawesome.min.css', $fontAwesomeCss);

// Download Font Awesome Fonts
$fontUrls = [
    'fa-brands-400.woff2',
    'fa-regular-400.woff2',
    'fa-solid-900.woff2'
];

foreach ($fontUrls as $font) {
    $fontContent = file_get_contents("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/webfonts/{$font}");
    file_put_contents("../assets/fonts/{$font}", $fontContent);
}

echo "Assets downloaded successfully!\n";