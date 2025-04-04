/**
 * StreamSnatcher - Theme Handler
 * Version: 1.0.0
 * Created: 2025-04-03 14:06:17
 * Author: tejvishwakarma
 */

class ThemeHandler {
    constructor() {
        this.themeToggle = document.getElementById('themeToggle');
        this.theme = localStorage.getItem('theme') || 'light';
        
        // Initialize theme
        this.setTheme(this.theme);
        
        // Bind event listeners
        this.themeToggle.addEventListener('click', () => {
            this.toggleTheme();
        });

        // Check system preference
        this.checkSystemPreference();
    }

    setTheme(theme) {
        document.documentElement.setAttribute('data-theme', theme);
        localStorage.setItem('theme', theme);
        this.theme = theme;
        
        // Update button aria-label
        this.themeToggle.setAttribute('aria-label', 
            `Switch to ${theme === 'light' ? 'dark' : 'light'} theme`
        );
    }

    toggleTheme() {
        const newTheme = this.theme === 'light' ? 'dark' : 'light';
        this.setTheme(newTheme);

        // Add animation class
        this.themeToggle.classList.add('theme-toggle-spin');
        setTimeout(() => {
            this.themeToggle.classList.remove('theme-toggle-spin');
        }, 300);
    }

    checkSystemPreference() {
        // Check if user hasn't set a theme preference
        if (!localStorage.getItem('theme')) {
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)');
            
            // Set initial theme based on system preference
            this.setTheme(prefersDark.matches ? 'dark' : 'light');

            // Listen for system theme changes
            prefersDark.addEventListener('change', (e) => {
                if (!localStorage.getItem('theme')) {
                    this.setTheme(e.matches ? 'dark' : 'light');
                }
            });
        }
    }
}

// Initialize theme handler
document.addEventListener('DOMContentLoaded', () => {
    window.themeHandler = new ThemeHandler();
});