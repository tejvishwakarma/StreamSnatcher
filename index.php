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

    <!-- New Header with Navigation -->
    <header class="main-header">
        <div class="container header-container">
            <div class="logo-container">
                <img src="assets/img/logo-dark-2.png" alt="StreamSnatcher Logo" class="logo dark-logo">
                <img src="assets/img/logo-dark.png" alt="StreamSnatcher Logo" class="logo light-logo">
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="index.php" class="active">Home</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="#faqs">FAQs</a></li>
                    <li><a href="#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="card">
            <form id="downloadForm" class="download-form">
                <div class="input-group">
                    <input type="url" id="videoUrl" name="url" placeholder="Paste video URL here..." required>
                    <button type="submit">Get Video</button>
                </div>
            </form>

            <div id="videoInfo" class="video-info hidden">
                <div class="video-section">
                    <div class="thumbnail-container">
                        <img id="thumbnail" src="" alt="Video thumbnail">
                    </div>
                    <div class="info-container">
                        <h2 id="videoTitle"></h2>
                        <p id="videoDuration"></p>
                        <p id="videoSize"></p>
                    </div>
                    <div class="format-selector">
                        <select id="formatSelect">
                            <option value="">Select quality...</option>
                        </select>
                        <button id="downloadBtn" disabled>Download</button>
                    </div>
                    <div id="downloadProgress" class="progress-container hidden">
                        <div class="progress-bar">
                            <div class="progress"></div>
                        </div>
                        <p class="progress-text">0%</p>
                    </div>
                </div>
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

        <!-- FAQs Section -->
        <section id="faqs" class="faqs-section">
            <h2>Frequently Asked Questions</h2>

            <div class="faq-container">
                <div class="faq-item">
                    <div class="faq-question">
                        <h3>How does StreamSnatcher work?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>StreamSnatcher works by analyzing the video URL you provide and extracting available download options. When you paste a URL and click "Get Video," our system identifies the source platform, retrieves video information (title, duration, available formats), and presents download options. After selecting your preferred quality, our service processes the download and delivers the file directly to your device.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is StreamSnatcher free to use?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Yes, StreamSnatcher is completely free to use. We provide all basic downloading features at no cost. There are no hidden fees or charges for downloading videos in standard formats and resolutions.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Which video platforms are supported?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>StreamSnatcher currently supports downloads from YouTube, Instagram, TikTok, Facebook, Twitter, Vimeo, Twitch, Dailymotion, Reddit, and LinkedIn. We continuously work to add support for more platforms and maintain compatibility as platforms update their systems.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>What video qualities and formats can I download?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>StreamSnatcher offers downloads in various qualities ranging from 144p to 4K (when available from the source). Common formats include MP4, WebM, and audio-only MP3. The available options depend on what the original platform provides. We display all available options when you submit a URL, allowing you to choose based on your preferences and storage constraints.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Is it legal to use StreamSnatcher?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>StreamSnatcher is a tool designed for downloading videos for personal use. The legality depends on the content you're downloading and your jurisdiction's copyright laws. We recommend only downloading content that you have permission to download, including public domain videos, creative commons content, your own content, or content where the creator explicitly allows downloading. Always respect copyright laws and the terms of service of the platforms you're downloading from.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Why isn't my download working?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>If you're experiencing issues with downloading, here are some common reasons:</p>
                        <ul>
                            <li>The video is private or protected content</li>
                            <li>The URL format is incorrect</li>
                            <li>The platform has recently updated its structure</li>
                            <li>Your internet connection is unstable</li>
                            <li>The video is region-restricted</li>
                        </ul>
                        <p>Try refreshing the page, verifying the URL, or contacting us if the issue persists.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Do you store the videos I download?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>No, StreamSnatcher does not store any videos on our servers. We act as an intermediary that processes the download request and delivers the content directly to your device. Once your download is complete, no copies remain on our system. This ensures both your privacy and efficient use of our resources.</p>
                    </div>
                </div>

                <div class="faq-item">
                    <div class="faq-question">
                        <h3>Can I download multiple videos at once?</h3>
                        <span class="faq-toggle"><i class="fas fa-chevron-down"></i></span>
                    </div>
                    <div class="faq-answer">
                        <p>Currently, StreamSnatcher processes one video at a time. You'll need to complete each download before starting another. We're exploring bulk download capabilities for future updates to enhance user experience and efficiency.</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Contact Section -->
        <section id="contact" class="contact-section">
            <h2>Contact Us</h2>

            <div class="contact-container">
                <div class="contact-info">
                    <p>Have questions, feedback, or need support? Reach out to us using the form below, and we'll get back to you as soon as possible.</p>

                    <div class="contact-methods">
                        <div class="contact-method">
                            <i class="fas fa-envelope"></i>
                            <h3>Email</h3>
                            <p>support@streamsnatcher.com</p>
                        </div>

                        <div class="contact-method">
                            <i class="fas fa-clock"></i>
                            <h3>Response Time</h3>
                            <p>Within 24-48 hours</p>
                        </div>
                    </div>
                </div>

                <div class="contact-form-container">
                    <form id="contactForm" class="contact-form">
                        <div class="form-group">
                            <label for="name">Name</label>
                            <input type="text" id="name" name="name" required>
                        </div>

                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="email" id="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label for="subject">Subject</label>
                            <select id="subject" name="subject">
                                <option value="general">General Inquiry</option>
                                <option value="support">Technical Support</option>
                                <option value="feedback">Feedback</option>
                                <option value="report">Report an Issue</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="message">Message</label>
                            <textarea id="message" name="message" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="submit-btn">Send Message</button>
                    </form>
                </div>
            </div>
        </section>
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
            <!-- <p>Created by <?php echo APP_AUTHOR; ?></p>
            <p>Version <?php echo APP_VERSION; ?></p> -->
        </div>
    </footer>

    <script src="assets/js/theme.js"></script>
    <script src="assets/js/app.js"></script>

    <!-- FAQ Toggle Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const faqItems = document.querySelectorAll('.faq-question');

            faqItems.forEach(item => {
                item.addEventListener('click', () => {
                    const parent = item.parentElement;
                    parent.classList.toggle('active');

                    const icon = item.querySelector('.faq-toggle i');
                    icon.classList.toggle('fa-chevron-down');
                    icon.classList.toggle('fa-chevron-up');
                });
            });
        });
    </script>

    <!-- Contact Form Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const contactForm = document.getElementById('contactForm');

            if (contactForm) {
                contactForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    // Normally you would send this data to your server
                    // For demo purposes, we'll just show a success message

                    const formData = new FormData(contactForm);
                    const name = formData.get('name');

                    // Create success alert
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-success';
                    alert.innerHTML = `
                        <div>
                            <strong>Message Sent!</strong> Thank you, ${name}. We'll be in touch soon.
                        </div>
                        <button type="button" class="close-alert" onclick="this.parentElement.remove()">&times;</button>
                    `;

                    // Insert alert before the form
                    contactForm.parentElement.insertBefore(alert, contactForm);

                    // Reset form
                    contactForm.reset();

                    // Remove alert after 5 seconds
                    setTimeout(() => {
                        if (alert.parentElement) {
                            alert.remove();
                        }
                    }, 5000);
                });
            }
        });
    </script>
</body>

</html>