<?php
/**
 * StreamSnatcher - Terms of Use Page
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
    <title>Terms of Use - StreamSnatcher</title>
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
            <h1>Terms of Use</h1>
            <p class="policy-date">Last Updated: April 4, 2025</p>
            
            <section class="policy-section">
                <h2>Introduction</h2>
                <p>Welcome to StreamSnatcher. These Terms of Use govern your use of the StreamSnatcher website and service. By using our website and service, you accept these Terms of Use in full. If you disagree with these Terms of Use or any part of them, you must not use our website or service.</p>
            </section>
            
            <section class="policy-section">
                <h2>Definitions</h2>
                <ul>
                    <li><strong>"Service"</strong> refers to the StreamSnatcher website, application, and video downloading functionality.</li>
                    <li><strong>"User"</strong> refers to any individual accessing or using our Service.</li>
                    <li><strong>"Content"</strong> refers to videos, audio, images, text, and other media that can be downloaded using our Service.</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>Acceptable Use</h2>
                <p>You agree to use our Service only for lawful purposes and in accordance with these Terms of Use. You agree not to use our Service:</p>
                <ul>
                    <li>In any way that violates any applicable local, national, or international law or regulation.</li>
                    <li>To download Content that infringes on any patent, trademark, trade secret, copyright, or other intellectual property rights of any party.</li>
                    <li>To transmit, or procure the sending of, any advertising or promotional material, including any "junk mail," "chain letter," "spam," or any other similar solicitation.</li>
                    <li>To impersonate or attempt to impersonate StreamSnatcher, a StreamSnatcher employee, another user, or any other person or entity.</li>
                    <li>To engage in any other conduct that restricts or inhibits anyone's use of the Service, or which may harm StreamSnatcher or users of the Service.</li>
                </ul>
                
                <p>Additionally, you agree not to:</p>
                <ul>
                    <li>Use the Service for unauthorized commercial purposes.</li>
                    <li>Attempt to decompile or reverse engineer any software contained on the StreamSnatcher website.</li>
                    <li>Remove any copyright or other proprietary notations from the materials.</li>
                    <li>Transfer the materials to another person or "mirror" the materials on any other server.</li>
                    <li>Use automated scripts or bots to access the Service.</li>
                    <li>Overload or flood the system with requests.</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>Intellectual Property and Copyright</h2>
                <p>StreamSnatcher respects intellectual property rights and expects its users to do the same. Our Service is designed to allow users to download Content for personal, non-commercial use only. You acknowledge and agree that:</p>
                <ul>
                    <li>The Service itself, including all associated software, design, text, and graphics, is the property of StreamSnatcher and is protected by copyright, trademark, and other intellectual property laws.</li>
                    <li>You are solely responsible for ensuring you have the right to download any Content using our Service.</li>
                    <li>Downloading Content that you do not have permission to download may violate copyright laws and the terms of service of the original content platform.</li>
                    <li>StreamSnatcher does not grant you ownership rights to any Content downloaded through our Service.</li>
                </ul>
                
                <p>StreamSnatcher complies with the Digital Millennium Copyright Act (DMCA). If you believe that Content available through our Service infringes on your copyright, please contact us with the following information:</p>
                <ul>
                    <li>A physical or electronic signature of the copyright owner or authorized agent.</li>
                    <li>Identification of the copyrighted work claimed to have been infringed.</li>
                    <li>A description of the infringing material and where it is located on our Service.</li>
                    <li>Your contact information.</li>
                    <li>A statement that you have a good faith belief that the use is not authorized by the copyright owner.</li>
                    <li>A statement, under penalty of perjury, that the information in your notice is accurate and that you are the copyright owner or authorized to act on the owner's behalf.</li>
                </ul>
            </section>
            
            <section class="policy-section">
                <h2>Service Availability and Modifications</h2>
                <p>We do not guarantee that our Service, or any Content on it, will always be available or uninterrupted. We reserve the right to withdraw or amend our Service, and any service or material we provide via our Service, in our sole discretion without notice. We will not be liable if, for any reason, all or any part of our Service is unavailable at any time or for any period.</p>
                <p>We may revise and update these Terms of Use from time to time in our sole discretion. All changes are effective immediately when we post them. Your continued use of the Service following the posting of revised Terms of Use means that you accept and agree to the changes.</p>
            </section>
            
            <section class="policy-section">
                <h2>Disclaimer of Warranties</h2>
                <p>You understand that we cannot and do not guarantee or warrant that files or data available for downloading from the internet or our Service will be free of viruses or other destructive code. You are responsible for implementing sufficient procedures and checkpoints to satisfy your particular requirements for antivirus protection and accuracy of data input and output, and for maintaining a means external to our site for any reconstruction of any lost data.</p>
                <p>TO THE FULLEST EXTENT PROVIDED BY LAW, WE WILL NOT BE LIABLE FOR ANY LOSS OR DAMAGE CAUSED BY A DISTRIBUTED DENIAL-OF-SERVICE ATTACK, VIRUSES, OR OTHER TECHNOLOGICALLY HARMFUL MATERIAL THAT MAY INFECT YOUR COMPUTER EQUIPMENT, COMPUTER PROGRAMS, DATA, OR OTHER PROPRIETARY MATERIAL DUE TO YOUR USE OF THE SERVICE OR ANY SERVICES OR ITEMS OBTAINED THROUGH THE SERVICE OR TO YOUR DOWNLOADING OF ANY MATERIAL POSTED ON IT, OR ON ANY WEBSITE LINKED TO IT.</p>
                <p>YOUR USE OF THE SERVICE, ITS CONTENT, AND ANY SERVICES OR ITEMS OBTAINED THROUGH THE SERVICE IS AT YOUR OWN RISK. THE SERVICE, ITS CONTENT, AND ANY SERVICES OR ITEMS OBTAINED THROUGH THE SERVICE ARE PROVIDED ON AN "AS IS" AND "AS AVAILABLE" BASIS, WITHOUT ANY WARRANTIES OF ANY KIND, EITHER EXPRESS OR IMPLIED.</p>
            </section>
            
            <section class="policy-section">
                <h2>Limitation of Liability</h2>
                <p>TO THE FULLEST EXTENT PROVIDED BY LAW, IN NO EVENT WILL STREAMSNATCHER, ITS AFFILIATES, OR THEIR LICENSORS, SERVICE PROVIDERS, EMPLOYEES, AGENTS, OFFICERS, OR DIRECTORS BE LIABLE FOR DAMAGES OF ANY KIND, UNDER ANY LEGAL THEORY, ARISING OUT OF OR IN CONNECTION WITH YOUR USE, OR INABILITY TO USE, THE SERVICE, ANY WEBSITES LINKED TO IT, ANY CONTENT ON THE WEBSITE OR SUCH OTHER WEBSITES, INCLUDING ANY DIRECT, INDIRECT, SPECIAL, INCIDENTAL, CONSEQUENTIAL, OR PUNITIVE DAMAGES, INCLUDING BUT NOT LIMITED TO, PERSONAL INJURY, PAIN AND SUFFERING, EMOTIONAL DISTRESS, LOSS OF REVENUE, LOSS OF PROFITS, LOSS OF BUSINESS OR ANTICIPATED SAVINGS, LOSS OF USE, LOSS OF GOODWILL, LOSS OF DATA, AND WHETHER CAUSED BY TORT (INCLUDING NEGLIGENCE), BREACH OF CONTRACT, OR OTHERWISE, EVEN IF FORESEEABLE.</p>
            </section>
            
            <section class="policy-section">
                <h2>Indemnification</h2>
                <p>You agree to defend, indemnify, and hold harmless StreamSnatcher, its affiliates, licensors, and service providers, and its and their respective officers, directors, employees, contractors, agents, licensors, suppliers, successors, and assigns from and against any claims, liabilities, damages, judgments, awards, losses, costs, expenses, or fees (including reasonable attorneys' fees) arising out of or relating to your violation of these Terms of Use or your use of the Service, including, but not limited to, any use of the Service's content and services other than as expressly authorized in these Terms of Use.</p>
            </section>
            
            <section class="policy-section">
                <h2>Governing Law</h2>
                <p>These Terms shall be governed and construed in accordance with the laws applicable in your jurisdiction, without regard to its conflict of law provisions. Our failure to enforce any right or provision of these Terms will not be considered a waiver of those rights.</p>
            </section>
            
            <section class="policy-section">
                <h2>Severability</h2>
                <p>If any provision of these Terms is held to be invalid or unenforceable by a court, the remaining provisions of these Terms will remain in effect.</p>
            </section>
            
            <section class="policy-section">
                <h2>Contact Us</h2>
                <p>If you have any questions about these Terms, please contact us:</p>
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
                <a href="privacy-policy.php">Privacy Policy</a>
                <span class="footer-divider">|</span>
                <a href="terms-of-use.php" class="active">Terms of Use</a>
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