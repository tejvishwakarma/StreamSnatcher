<?php
/**
 * StreamSnatcher - Privacy Policy Page
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
    <title>Privacy Policy - StreamSnatcher</title>
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
                    <li><a href="about.php">About</a></li>
                    <li><a href="index.php#faqs">FAQs</a></li>
                    <li><a href="index.php#contact">Contact</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <main class="container">
        <div class="page-content policy-page">
            <h1>Privacy Policy</h1>
            <p class="policy-date">Last Updated: April 4, 2025</p>
            
            <section class="policy-section">
                <h2>Introduction</h2>
                <p>Welcome to StreamSnatcher. We respect your privacy and are committed to protecting your personal data. This privacy policy will inform you about how we look after your personal data when you visit our website and tell you about your privacy rights and how the law protects you.</p>
                <p>Please read this privacy policy carefully before using our service.</p>
            </section>
            
            <section class="policy-section">
                <h2>What Data We Collect</h2>
                <p>When you use StreamSnatcher, we collect minimal information to provide our services. We do not require user registration or accounts to use our basic services. The information we collect falls into these categories:</p>
                
                <h3>Technical Data</h3>
                <ul>
                    <li>IP address (temporarily stored)</li>
                    <li>Browser type and version</li>
                    <li>Operating system information</li>
                    <li>Device information</li>
                    <li>Cookies and similar tracking technologies</li>
                </ul>
                
                <h3>Usage Data</h3>
                <ul>
                    <li>URLs submitted for downloading</li>
                    <li>Download preferences (formats, quality)</li>
                    <li>Time and date of service use</li>
                </ul>
                
                <p>We <strong>do not</strong> collect or store:</p>
                <ul>
                    <li>Personal identification information (name, email, etc.) unless you explicitly provide it</li>
                    <li>Payment information</li>
                    <li>The content of videos you download</li>
                    <li>Browsing history beyond your interactions with our service</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>How We Use Your Data</h2>
                <p>We use the collected data for the following purposes:</p>
                <ul>
                    <li>To provide and maintain our service</li>
                    <li>To detect, prevent, and address technical issues</li>
                    <li>To improve our user experience</li>
                    <li>To monitor the usage of our service</li>
                    <li>To gather analysis or valuable information to improve our service</li>
                </ul>
                <p>We process this data based on our legitimate interest in providing and improving our service. The URLs you submit are processed solely to fulfill the downloading function you request.</p>
            </section>
            
            <section class="policy-section">
                <h2>Data Storage and Security</h2>
                <p>We implement appropriate security measures to protect against unauthorized access, alteration, disclosure, or destruction of your data. Technical data is typically stored for no more than 30 days and is then automatically deleted.</p>
                <p>URLs submitted for downloading are processed in real-time and are not permanently stored in our database. They are only temporarily stored in server memory during the processing of your request.</p>
                <p>While we strive to use commercially acceptable means to protect your personal data, we cannot guarantee its absolute security. Internet transmission is never completely secure.</p>
            </section>
            
            <section class="policy-section">
                <h2>Cookies and Tracking</h2>
                <p>We use cookies and similar tracking technologies to track activity on our service and hold certain information. Cookies are files with a small amount of data which may include an anonymous unique identifier.</p>
                <p>You can instruct your browser to refuse all cookies or to indicate when a cookie is being sent. However, if you do not accept cookies, you may not be able to use some portions of our service.</p>
                <p>We use the following types of cookies:</p>
                <ul>
                    <li><strong>Essential cookies:</strong> Necessary for the operation of the website</li>
                    <li><strong>Preference cookies:</strong> Enable the website to remember your preferences (like dark/light theme)</li>
                    <li><strong>Analytics cookies:</strong> Help us understand how visitors interact with the website</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>Third-Party Services</h2>
                <p>We may employ third-party companies and individuals to facilitate our service ("Service Providers"), provide the service on our behalf, perform service-related activities, or assist us in analyzing how our service is used.</p>
                <p>These third parties have access to your personal data only to perform these tasks on our behalf and are obligated not to disclose or use it for any other purpose.</p>
                <p>We currently use the following third-party services:</p>
                <ul>
                    <li>Server hosting providers</li>
                    <li>Analytics providers</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>Children's Privacy</h2>
                <p>Our service does not address anyone under the age of 13. We do not knowingly collect personally identifiable information from anyone under the age of 13. If you are a parent or guardian and you are aware that your child has provided us with personal data, please contact us. If we become aware that we have collected personal data from children without verification of parental consent, we take steps to remove that information from our servers.</p>
            </section>
            
            <section class="policy-section">
                <h2>Changes to This Privacy Policy</h2>
                <p>We may update our Privacy Policy from time to time. We will notify you of any changes by posting the new Privacy Policy on this page and updating the "Last Updated" date at the top of this Privacy Policy.</p>
                <p>You are advised to review this Privacy Policy periodically for any changes. Changes to this Privacy Policy are effective when they are posted on this page.</p>
            </section>
            
            <section class="policy-section">
                <h2>Contact Us</h2>
                <p>If you have any questions about this Privacy Policy, please contact us:</p>
                <ul>
                    <li>By visiting the contact section on our website: <a href="index.php#contact">Contact Page</a></li>
                </ul>
            </section>
        </div>
    </main>

    <!-- Footer with Navigation -->
    <footer class="footer">
        <div class="footer-content">
            <div class="footer-nav">
                <a href="privacy-policy.php" class="active">Privacy Policy</a>
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