<?php
/**
 * StreamSnatcher - About Page
 * Version: 1.0.0
 * Created: 2025-04-04
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
    <title>About - StreamSnatcher</title>
    <link rel="stylesheet" href="assets/css/theme.css">
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="shortcut icon" href="assets/img/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/fontawesome.min.css">

    <!-- CSP meta tag -->
    <meta http-equiv="Content-Security-Policy"
        content="default-src 'self' 'unsafe-inline' 'unsafe-eval' data: blob:;
                   img-src 'self' data: blob: http://i1.hdslb.com https://i.ytimg.com https://scontent.cdninstagram.com https://fbcdn.net;
                   media-src 'self' data: blob:;
                   connect-src 'self';
                   script-src 'self' 'unsafe-inline' 'unsafe-eval';
                   style-src 'self' 'unsafe-inline';
                   font-src 'self' data:;">
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

    <!-- Header with Navigation -->
    <header class="main-header">
        <div class="container header-container">
            <div class="logo-container">
                <img src="assets/img/logo-dark.png" alt="StreamSnatcher Logo" class="logo dark-logo">
                <img src="assets/img/logo-light.png" alt="StreamSnatcher Logo" class="logo light-logo">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="about.php" class="active">About</a></li>
                    <li><a href="index.php#faqs">FAQs</a></li>
                    <li><a href="index.php#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="page-content about-page">
            <h1>About StreamSnatcher</h1>
            
            <section class="about-section">
                <h2>Our Mission</h2>
                <p>StreamSnatcher was created with a simple mission: to provide users with a seamless, high-quality way to download videos from their favorite platforms for offline viewing. We understand that internet access isn't always available, and sometimes you need to save content for later viewing. That's where we come in.</p>
            </section>
            
            <section class="about-section">
                <h2>What We Offer</h2>
                <p>StreamSnatcher is a powerful, user-friendly video downloading tool that supports multiple popular platforms including YouTube, Instagram, TikTok, Facebook, Twitter, Vimeo, and more. Our service allows you to:</p>
                
                <ul class="feature-list">
                    <li>Download videos in multiple resolutions</li>
                    <li>Choose between audio and video formats</li>
                    <li>Process downloads quickly and efficiently</li>
                    <li>Save content for offline viewing</li>
                    <li>Access your favorite content anytime, anywhere</li>
                </ul>
            </section>
            
            <section class="about-section">
                <h2>Our Technology</h2>
                <p>Built with modern web technologies, StreamSnatcher utilizes advanced techniques to provide reliable downloading capabilities across multiple platforms. Our system is designed to adapt to platform changes and continues to work even when sites update their interfaces.</p>
                
                <p>We prioritize:</p>
                <ul class="feature-list">
                    <li>Speed and efficiency</li>
                    <li>User privacy and security</li>
                    <li>Intuitive interface design</li>
                    <li>Cross-platform compatibility</li>
                    <li>Regular updates and maintenance</li>
                </ul>
            </section>
            
            <section class="about-section">
                <h2>Responsible Use</h2>
                <p>While StreamSnatcher provides video downloading capabilities, we encourage all users to respect copyright laws and the terms of service of the content platforms. Our tool is designed for personal use with content that you have the right to download.</p>
                
                <p>We recommend using StreamSnatcher to:</p>
                <ul class="feature-list">
                    <li>Download content you've created</li>
                    <li>Save videos that are explicitly shared with permission for downloading</li>
                    <li>Access public domain or creative commons licensed content</li>
                    <li>Download content for offline personal viewing where permitted</li>
                </ul>
            </section>
            
            <section class="about-section">
                <h2>Future Development</h2>
                <p>StreamSnatcher is continuously evolving. We're committed to improving our service by adding new features, supporting additional platforms, and enhancing user experience. We value feedback from our users and work diligently to implement requested features and fix any reported issues.</p>
                
                <p>Some of our planned future enhancements include:</p>
                <ul class="feature-list">
                    <li>Batch downloading capabilities</li>
                    <li>Scheduled downloads</li>
                    <li>Browser extensions</li>
                    <li>Additional platform support</li>
                    <li>Advanced format conversion options</li>
                </ul>
            </section>
            
            <section class="about-section">
                <h2>The Team</h2>
                <p>StreamSnatcher was developed by <?php echo APP_AUTHOR; ?>, a passionate developer dedicated to creating practical tools that enhance the digital experience. Our small team works tirelessly to maintain and improve the service, ensuring it remains relevant and functional in an ever-changing digital landscape.</p>
            </section>
            
            <div class="cta-section">
                <h2>Ready to try StreamSnatcher?</h2>
                <p>Experience the easiest way to download your favorite videos for offline viewing.</p>
                <a href="index.php" class="cta-button">Get Started Now</a>
            </div>
        </div>
    </main>

    <!-- Footer with Navigation -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-nav">
                <a href="privacy-policy.php">Privacy Policy</a>
                <span class="footer-divider">|</span>
                <a href="terms-of-use.php">Terms of Use</a>
            </div>
            <p>&copy; <?php echo date('Y'); ?> StreamSnatcher. All rights reserved.</p>
            <p>Created by <?php echo APP_AUTHOR; ?></p>
            <p>Version <?php echo APP_VERSION; ?></p>
        </div>
    </footer>

    <script src="assets/js/theme.js"></script>
    <script src="assets/js/app.js"></script>
</body>

</html>