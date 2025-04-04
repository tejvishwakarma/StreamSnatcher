<?php

/**
 * StreamSnatcher - Main Application Page
 * Version: 1.0.0
 * Created: 2025-04-03 14:14:24
 * Author: tejvishwakarma
 */

// Initialize application
define('STREAMSNATCHER', true);
require_once 'includes/config.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StreamSnatcher - Video Downloader</title>
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"> -->

    <!-- CSP meta tag -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:;
                   img-src 'self' data: blob: http://i1.hdslb.com https://i.ytimg.com https://scontent.cdninstagram.com https://fbcdn.net;
                   media-src 'self' data: blob:;
                   connect-src 'self';
                   script-src 'self' 'unsafe-inline' 'unsafe-eval';
                   style-src 'self' 'unsafe-inline';
                   font-src 'self' data:;">
                   <meta http-equiv="Content-Security-Policy" content="img-src 'self' data: blob: https: http:">
    <!-- Local Font Awesome CSS -->
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">

    <!-- Local Fonts -->
    <style>
        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-style: normal;
            font-weight: 900;
            src: url('assets/fonts/fa-solid-900.woff2') format('woff2');
        }

        @font-face {
            font-family: 'Font Awesome 6 Free';
            font-style: normal;
            font-weight: 400;
            src: url('assets/fonts/fa-regular-400.woff2') format('woff2');
        }

        @font-face {
            font-family: 'Font Awesome 6 Brands';
            font-style: normal;
            font-weight: 400;
            src: url('assets/fonts/fa-brands-400.woff2') format('woff2');
        }

        /* Roboto font */
        @font-face {
            font-family: 'Roboto';
            font-style: normal;
            font-weight: 400;
            src: local('Roboto'), local('Roboto-Regular'),
                url('assets/fonts/roboto-regular.woff2') format('woff2');
        }

        body {
            font-family: 'Roboto', sans-serif;
        }
    </style>
</head>

<body>
    <div class="theme-toggle">
        <button id="themeToggle" aria-label="Toggle theme">
            <svg class="sun-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M12 1v2M12 21v2M4.22 4.22l1.42 1.42M18.36 18.36l1.42 1.42M1 12h2M21 12h2M4.22 19.78l1.42-1.42M18.36 5.64l1.42-1.42" />
                <circle cx="12" cy="12" r="5" />
            </svg>
            <svg class="moon-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                <path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z" />
            </svg>
        </button>
    </div>

    <main class="container">
        <div class="logo-container">
            <img src="assets/img/logo-dark.png" alt="StreamSnatcher Logo" class="logo">
        </div>

        <!-- <h1>StreamSnatcher</h1> -->

        <div class="card">
            <form id="downloadForm" class="download-form">
                <div class="input-group">
                    <input type="url" id="videoUrl" name="url" placeholder="Paste video URL here..." required>
                    <button type="submit">Get Video</button>
                </div>
            </form>

            <div id="videoInfo" class="video-info hidden">
                <div class="thumbnail-container">
                    <img id="thumbnail" src="" alt="Video thumbnail">
                </div>
                <div class="info-container">
                    <h2 id="videoTitle"></h2>
                    <p id="videoDuration"></p>
                    <div class="format-selector">
                        <select id="formatSelect">
                            <option value="">Select quality...</option>
                        </select>
                        <button id="downloadBtn" disabled>Download</button>
                    </div>
                </div>
            </div>

            <div id="downloadProgress" class="progress-container hidden">
                <div class="progress-bar">
                    <div class="progress"></div>
                </div>
                <p class="progress-text">0%</p>
            </div>
        </div>

        <section class="supported-sites">
            <h2>Supported Platforms</h2>
            <div class="sites-grid">
                <div class="site-card">
                    <i class="fab fa-youtube"></i>
                    <span>YouTube</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-instagram"></i>
                    <span>Instagram</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-tiktok"></i>
                    <span>TikTok</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-facebook"></i>
                    <span>Facebook</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-twitter"></i>
                    <span>Twitter</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-vimeo-v"></i>
                    <span>Vimeo</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-twitch"></i>
                    <span>Twitch</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-dailymotion"></i>
                    <span>Dailymotion</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-reddit"></i>
                    <span>Reddit</span>
                </div>
                <div class="site-card">
                    <i class="fab fa-linkedin"></i>
                    <span>LinkedIn</span>
                </div>
            </div>
        </section>
    </main>

    <footer class="footer">
        <div class="footer-content">
            <p>&copy; <?php echo date('Y'); ?> StreamSnatcher. All rights reserved.</p>
            <p>Created by <?php echo APP_AUTHOR; ?></p>
            <p>Version <?php echo APP_VERSION; ?></p>
        </div>
    </footer>

    <script src="assets/js/theme.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>