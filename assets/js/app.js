/**
 * StreamSnatcher - Frontend Application
 * Version: 1.0.0
 * Created: 2025-04-03 17:40:06
 * Author: tejvishwakarma
 */

class StreamSnatcher {
    constructor() {
        this.version = '1.0.0';
        this.created = '2025-04-03 17:40:06';
        this.author = 'tejvishwakarma';
        
        const baseUrl = window.location.pathname.substring(0, window.location.pathname.indexOf('/StreamSnatcher')) + '/StreamSnatcher';
        this.proxyUrl = `${baseUrl}/api/proxy.php`;

        this.init();
    }

    // Add proxy method
    getProxiedImageUrl(originalUrl) {
        return `${this.proxyUrl}?url=${encodeURIComponent(originalUrl)}`;
    }

    // Update thumbnail display
    displayVideoInfo(data) {
        const videoInfo = document.getElementById('videoInfo');
        const thumbnail = document.getElementById('thumbnail');
        const title = document.getElementById('videoTitle');
        const duration = document.getElementById('videoDuration');
        const formatSelect = document.getElementById('formatSelect');
        const downloadBtn = document.getElementById('downloadBtn');

        // Use proxy for thumbnail
        if (data.thumbnail) {
            thumbnail.src = this.proxyImage(data.thumbnail);
        };
    }

    init() {
        // Initialize form elements
        this.form = document.getElementById('downloadForm');
        this.urlInput = document.getElementById('videoUrl');
        this.formatSelect = document.getElementById('formatSelect');
        this.downloadBtn = document.getElementById('downloadBtn');
        this.progressContainer = document.getElementById('downloadProgress');
        this.progressBar = this.progressContainer.querySelector('.progress');
        this.progressText = this.progressContainer.querySelector('.progress-text');
        this.videoInfo = document.getElementById('videoInfo');
        this.thumbnail = document.getElementById('thumbnail');
        this.videoTitle = document.getElementById('videoTitle');
        this.videoDuration = document.getElementById('videoDuration');

        // Verify all elements are found
        if (!this.form || !this.urlInput || !this.formatSelect || !this.downloadBtn ||
            !this.progressContainer || !this.progressBar || !this.progressText ||
            !this.videoInfo || !this.thumbnail || !this.videoTitle || !this.videoDuration) {
            console.error('Required elements not found in the DOM');
            return;
        }

        // Add event listeners
        this.form.addEventListener('submit', this.handleSubmit.bind(this));
        this.formatSelect.addEventListener('change', this.handleFormatChange.bind(this));
        this.downloadBtn.addEventListener('click', this.handleDownload.bind(this));
    }

    async handleSubmit(e) {
        e.preventDefault();

        const url = this.urlInput.value.trim();
        if (!url) {
            this.showError('Please enter a valid URL');
            return;
        }

        const submitButton = e.target.querySelector('button');
        try {
            // Reset previous state
            this.resetState();

            // Show loading state
            this.urlInput.disabled = true;
            submitButton.disabled = true;
            submitButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing...';

            // Make the API request
            const response = await fetch('./api/info.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ url })
            });

            const responseText = await response.text();
            console.log('Raw API Response:', responseText);

            let result;
            try {
                result = JSON.parse(responseText.trim());
            } catch (parseError) {
                console.error('JSON Parse Error:', parseError);
                throw new Error(`Invalid server response: ${parseError.message}`);
            }

            if (!response.ok || !result.success) {
                throw new Error(result.error || 'Failed to fetch video information');
            }

            // Display the video information
            if (result.data) {
                this.displayVideoInfo(result.data);
            } else {
                throw new Error('No video data received');
            }

        } catch (error) {
            console.error('Request Error:', error);
            this.showError(error.message);
        } finally {
            // Reset loading state
            this.urlInput.disabled = false;
            submitButton.disabled = false;
            submitButton.innerHTML = 'Get Video';
        }
    }

    displayVideoInfo(data) {
        // Show video info container
        this.videoInfo.classList.remove('hidden');
    
        // Update thumbnail with proxy
        if (data.thumbnail) {
            // Clear previous thumbnail and set loading state
            this.thumbnail.src = '';
            this.thumbnail.alt = 'Loading...';
    
            // Create a new Image object to preload
            const img = new Image();
            img.onload = () => {
                this.thumbnail.src = img.src;
                this.thumbnail.alt = `Thumbnail for ${data.title}`;
            };
            img.onerror = () => {
                console.error('Failed to load image:', data.thumbnail);
                this.thumbnail.alt = 'Failed to load thumbnail';
                // Set a fallback image
                this.thumbnail.src = './assets/img/thumbnail-error.png';
            };
    
            // Use the proxy URL
            img.src = this.getProxiedImageUrl(data.thumbnail);
            console.log('Proxied thumbnail URL:', img.src); // Debug log
        }
    
        // Update video details
        this.videoTitle.textContent = data.title || 'Unknown Title';
        this.videoDuration.textContent = this.formatDuration(data.duration);
    
        // Update format select options
        if (Array.isArray(data.formats)) {
            // Filter and group formats
            const audioFormats = this.getBestAudioFormats(data.formats);
            const videoFormats = this.getBestVideoFormats(data.formats);
    
            // Create option groups
            this.formatSelect.innerHTML = `
                <option value="">Select quality...</option>
                ${videoFormats.length ? `
                    <optgroup label="Video">
                        ${videoFormats.map(format => `
                            <option value="${format.format_id}">
                                ${format.quality} ${format.ext.toUpperCase()} - ${format.filesize || 'Unknown size'}
                            </option>
                        `).join('')}
                    </optgroup>
                ` : ''}
                ${audioFormats.length ? `
                    <optgroup label="Audio">
                        ${audioFormats.map(format => `
                            <option value="${format.format_id}">
                                ${format.quality} ${format.ext.toUpperCase()} - ${format.filesize || 'Unknown size'}
                            </option>
                        `).join('')}
                    </optgroup>
                ` : ''}
            `;
    
            this.formatSelect.disabled = false;
            this.downloadBtn.disabled = true;
        } else {
            this.showError('No formats available for this video');
        }
    }

    getBestAudioFormats(formats) {
        // Filter audio formats
        const audioFormats = formats.filter(f => f.type === 'audio');

        // Group by extension
        const groupedByExt = {};
        audioFormats.forEach(format => {
            if (!groupedByExt[format.ext]) {
                groupedByExt[format.ext] = [];
            }
            groupedByExt[format.ext].push(format);
        });

        // Get best quality for each extension
        return Object.values(groupedByExt).map(group => {
            return group.reduce((best, current) => {
                const bestKbps = parseInt(best.quality) || 0;
                const currentKbps = parseInt(current.quality) || 0;
                return currentKbps > bestKbps ? current : best;
            });
        });
    }

    getBestVideoFormats(formats) {
        // Filter video formats and extract resolutions
        const videoFormats = formats.filter(f => f.type === 'video');
        const resolutions = new Set(videoFormats.map(f => {
            const match = f.quality.match(/(\d+)p/);
            return match ? parseInt(match[1]) : 0;
        }));

        // Get best format for each resolution
        return Array.from(resolutions)
            .filter(res => res > 0)
            .sort((a, b) => b - a) // Sort resolutions in descending order
            .map(resolution => {
                const formatsForResolution = videoFormats.filter(f => f.quality.includes(`${resolution}p`));
                return formatsForResolution.reduce((best, current) => {
                    // Prefer MP4 over other formats
                    if (best.ext === 'mp4' && current.ext !== 'mp4') return best;
                    if (best.ext !== 'mp4' && current.ext === 'mp4') return current;

                    // If same extension, prefer the one with known filesize
                    if (best.filesize !== 'Unknown' && current.filesize === 'Unknown') return best;
                    if (best.filesize === 'Unknown' && current.filesize !== 'Unknown') return current;

                    // If both have filesizes, prefer the larger one (usually better quality)
                    if (best.filesize && current.filesize) {
                        const bestSize = parseFloat(best.filesize);
                        const currentSize = parseFloat(current.filesize);
                        return currentSize > bestSize ? current : best;
                    }

                    return best;
                });
            });
    }

    formatDuration(seconds) {
        if (!seconds) return 'Unknown duration';
        const hours = Math.floor(seconds / 3600);
        const minutes = Math.floor((seconds % 3600) / 60);
        const secs = seconds % 60;

        if (hours > 0) {
            return `${hours}:${minutes.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
        }
        return `${minutes}:${secs.toString().padStart(2, '0')}`;
    }

    async handleDownload() {
        const formatId = this.formatSelect.value;
        const url = this.urlInput.value.trim();
        const videoTitle = this.videoTitle.textContent;

        // Determine if this is an audio-only download
        const selectedOption = this.formatSelect.options[this.formatSelect.selectedIndex];
        const isAudioOnly = selectedOption.parentElement.label === 'Audio';

        if (!formatId || !url) {
            this.showError('Please select a format first');
            return;
        }

        try {
            // Update UI
            this.downloadBtn.disabled = true;
            this.downloadBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Starting download...';
            this.progressContainer.classList.remove('hidden');
            this.progressBar.style.width = '0%';
            this.updateProgress(0);

            // Make download request
            const response = await fetch('./api/download.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    url,
                    formatId,
                    title: videoTitle,
                    isAudioOnly // Add this flag
                })
            });

            // Check response status
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.error || 'Download request failed');
            }

            // Handle the response
            const contentType = response.headers.get('content-type');

            if (contentType && contentType.includes('application/json')) {
                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.error || 'Download failed');
                }
                throw new Error('Invalid server response');
            } else {
                const blob = await response.blob();

                // For video files, check size
                if (!isAudioOnly && blob.size === 0) {
                    throw new Error('Downloaded file is empty');
                }

                // Get filename from header or create one
                let filename = '';
                const disposition = response.headers.get('content-disposition');

                if (disposition && disposition.includes('filename=')) {
                    const filenameMatch = disposition.match(/filename[^;=\n]*=((['"]).*?\2|[^;\n]*)/);
                    if (filenameMatch && filenameMatch[1]) {
                        filename = filenameMatch[1].replace(/['"]/g, '');
                    }
                }

                if (!filename) {
                    const ext = selectedOption.textContent.split(' ')[1]?.toLowerCase() || (isAudioOnly ? 'mp3' : 'mp4');
                    filename = `${videoTitle.replace(/[^a-z0-9]/gi, '_')}.${ext}`;
                }

                // Create and trigger download
                const downloadUrl = window.URL.createObjectURL(blob);
                const a = document.createElement('a');
                a.href = downloadUrl;
                a.download = filename;
                document.body.appendChild(a);

                try {
                    a.click();
                    this.updateProgress(100);
                    this.showSuccess(`${isAudioOnly ? 'Audio' : 'Video'} downloaded successfully!`);
                } catch (downloadError) {
                    throw new Error(`Failed to download file: ${downloadError.message}`);
                } finally {
                    // Cleanup
                    window.URL.revokeObjectURL(downloadUrl);
                    document.body.removeChild(a);
                }
            }

        } catch (error) {
            console.error('Download Error:', error);
            this.showError(error.message || 'Download failed');
        } finally {
            // Reset UI
            this.downloadBtn.disabled = false;
            this.downloadBtn.innerHTML = 'Download';
        }
    }

    updateProgress(percent) {
        const progress = Math.min(100, Math.max(0, percent));
        this.progressBar.style.width = `${progress}%`;
        this.progressText.textContent = `${Math.round(progress)}%`;
    }

    resetState() {
        this.videoInfo.classList.add('hidden');
        this.progressContainer.classList.add('hidden');
        this.formatSelect.innerHTML = '<option value="">Select quality...</option>';
        this.formatSelect.disabled = true;
        this.downloadBtn.disabled = true;
        this.progressBar.style.width = '0%';
        this.progressText.textContent = '0%';

        // Clear previous alerts
        document.querySelectorAll('.alert').forEach(alert => alert.remove());
    }

    showError(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-error';
        alertDiv.innerHTML = `
            <div class="alert-content">
                <i class="fas fa-exclamation-circle"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="close-alert">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.form.parentNode.insertBefore(alertDiv, this.form);

        const closeButton = alertDiv.querySelector('.close-alert');
        if (closeButton) {
            closeButton.addEventListener('click', () => alertDiv.remove());
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    showSuccess(message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = 'alert alert-success';
        alertDiv.innerHTML = `
            <div class="alert-content">
                <i class="fas fa-check-circle"></i>
                <span>${message}</span>
            </div>
            <button type="button" class="close-alert">
                <i class="fas fa-times"></i>
            </button>
        `;

        this.form.parentNode.insertBefore(alertDiv, this.form);

        const closeButton = alertDiv.querySelector('.close-alert');
        if (closeButton) {
            closeButton.addEventListener('click', () => alertDiv.remove());
        }

        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    handleFormatChange() {
        this.downloadBtn.disabled = !this.formatSelect.value;
    }
}

// Initialize the application
document.addEventListener('DOMContentLoaded', () => {
    try {
        window.streamSnatcher = new StreamSnatcher();
    } catch (error) {
        console.error('Failed to initialize StreamSnatcher:', error);
    }
});