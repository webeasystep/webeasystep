/**
 * Bunny.net Video Player Integration
 * Handles secure video streaming with progress tracking
 */
class BunnyVideoPlayer {
    constructor(containerId, unitId, options = {}) {
        this.containerId = containerId;
        this.unitId = unitId;
        this.options = {
            autoplay: false,
            controls: true,
            responsive: true,
            fluid: true,
            playbackRates: [0.5, 1, 1.25, 1.5, 2],
            enableProgressTracking: true,
            progressUpdateInterval: 5000,
            ...options
        };
        
        this.player = null;
        this.videoData = null;
        this.progressTracker = null;
        this.tokenRefreshTimer = null;
        
        this.init();
    }
    
    async init() {
        try {
            // Load video data from server
            await this.loadVideoData();
            
            // Initialize video player
            this.initializePlayer();
            
            // Setup progress tracking if enabled
            if (this.options.enableProgressTracking) {
                this.initializeProgressTracking();
            }
            
            // Setup token refresh
            this.setupTokenRefresh();
            
        } catch (error) {
            console.error('Failed to initialize Bunny video player:', error);
            this.showError('Failed to load video. Please try again later.');
        }
    }
    
    async loadVideoData() {
        const response = await fetch(`/videos/stream/${this.unitId}`);
        const data = await response.json();
        
        if (!data.success) {
            throw new Error(data.message || 'Failed to load video data');
        }
        
        this.videoData = data;
        return data;
    }
    
    initializePlayer() {
        const container = document.getElementById(this.containerId);
        if (!container) {
            throw new Error(`Container with ID '${this.containerId}' not found`);
        }
        
        // Create video element
        const videoElement = document.createElement('video');
        videoElement.id = `bunny-player-${this.unitId}`;
        videoElement.className = 'video-js vjs-default-skin';
        videoElement.setAttribute('controls', '');
        videoElement.setAttribute('preload', 'metadata');
        videoElement.setAttribute('data-setup', '{}');
        
        if (this.videoData.thumbnail_url) {
            videoElement.setAttribute('poster', this.videoData.thumbnail_url);
        }
        
        // Add video sources
        const sources = [];
        
        // Add HLS source (preferred for adaptive streaming)
        if (this.videoData.video_urls.hls) {
            sources.push({
                src: this.videoData.video_urls.hls,
                type: 'application/x-mpegURL'
            });
        }
        
        // Add MP4 source (fallback)
        if (this.videoData.video_urls.mp4) {
            sources.push({
                src: this.videoData.video_urls.mp4,
                type: 'video/mp4'
            });
        }
        
        // Add DASH source if available
        if (this.videoData.video_urls.dash) {
            sources.push({
                src: this.videoData.video_urls.dash,
                type: 'application/dash+xml'
            });
        }
        
        container.appendChild(videoElement);
        
        // Initialize Video.js player
        this.player = videojs(videoElement.id, {
            responsive: this.options.responsive,
            fluid: this.options.fluid,
            playbackRates: this.options.playbackRates,
            sources: sources,
            html5: {
                vhs: {
                    overrideNative: true
                },
                nativeVideoTracks: false,
                nativeAudioTracks: false,
                nativeTextTracks: false
            }
        });
        
        // Setup player event listeners
        this.setupPlayerEvents();
        
        // Load saved progress position
        this.loadSavedProgress();
    }
    
    setupPlayerEvents() {
        if (!this.player) return;
        
        // Player ready event
        this.player.ready(() => {
            console.log('Bunny video player ready');
            if (this.options.onReady) {
                this.options.onReady(this.player);
            }
        });
        
        // Play event
        this.player.on('play', () => {
            console.log('Video started playing');
            if (this.options.onPlay) {
                this.options.onPlay();
            }
        });
        
        // Pause event
        this.player.on('pause', () => {
            console.log('Video paused');
            if (this.options.onPause) {
                this.options.onPause();
            }
        });
        
        // Ended event
        this.player.on('ended', () => {
            console.log('Video ended');
            if (this.options.onEnded) {
                this.options.onEnded();
            }
        });
        
        // Error event
        this.player.on('error', (error) => {
            console.error('Video player error:', error);
            this.handlePlayerError(error);
        });
        
        // Time update for progress tracking
        this.player.on('timeupdate', () => {
            if (this.progressTracker) {
                this.progressTracker.updateProgress();
            }
        });
    }
    
    initializeProgressTracking() {
        if (typeof VideoProgressTracker === 'undefined') {
            console.warn('VideoProgressTracker not available. Progress tracking disabled.');
            return;
        }
        
        this.progressTracker = new VideoProgressTracker(
            this.player.el().querySelector('video'),
            this.unitId,
            {
                updateInterval: this.options.progressUpdateInterval,
                apiEndpoint: '/progress/update',
                onProgressUpdate: (progress, isCompleted) => {
                    if (this.options.onProgressUpdate) {
                        this.options.onProgressUpdate(progress, isCompleted);
                    }
                },
                onUnitCompleted: (result) => {
                    if (this.options.onUnitCompleted) {
                        this.options.onUnitCompleted(result);
                    }
                },
                onError: (error) => {
                    console.error('Progress tracking error:', error);
                }
            }
        );
    }
    
    async loadSavedProgress() {
        try {
            const response = await fetch(`/progress/unit-progress?unit_id=${this.unitId}`);
            const data = await response.json();
            
            if (data.success && data.progress && data.progress.last_position_seconds > 0) {
                // Resume from last position after player is ready
                this.player.ready(() => {
                    setTimeout(() => {
                        this.player.currentTime(data.progress.last_position_seconds);
                        this.showResumeNotification(data.progress.last_position_seconds);
                    }, 1000);
                });
            }
        } catch (error) {
            console.error('Failed to load saved progress:', error);
        }
    }
    
    showResumeNotification(position) {
        const minutes = Math.floor(position / 60);
        const seconds = Math.floor(position % 60);
        const timeString = `${minutes}:${seconds.toString().padStart(2, '0')}`;
        
        // Create resume notification
        const notification = document.createElement('div');
        notification.className = 'video-resume-notification';
        notification.innerHTML = `
            <div class="resume-content">
                <p>Resume from ${timeString}?</p>
                <button class="btn btn-primary btn-sm" onclick="this.parentElement.parentElement.remove()">Continue</button>
                <button class="btn btn-secondary btn-sm" onclick="this.parentElement.parentElement.remove(); document.querySelector('#${this.containerId} video').currentTime = 0;">Start Over</button>
            </div>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .video-resume-notification {
                position: absolute;
                top: 20px;
                right: 20px;
                background: rgba(0, 0, 0, 0.8);
                color: white;
                padding: 15px;
                border-radius: 5px;
                z-index: 1000;
                max-width: 250px;
            }
            .resume-content p {
                margin: 0 0 10px 0;
                font-size: 14px;
            }
            .resume-content button {
                margin-right: 5px;
                font-size: 12px;
                padding: 5px 10px;
            }
        `;
        
        document.head.appendChild(style);
        
        // Add to player container
        const playerContainer = document.getElementById(this.containerId);
        playerContainer.style.position = 'relative';
        playerContainer.appendChild(notification);
        
        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }
    
    setupTokenRefresh() {
        if (!this.videoData || !this.videoData.expires_at) return;
        
        const expiresAt = this.videoData.expires_at * 1000; // Convert to milliseconds
        const refreshTime = expiresAt - (30 * 60 * 1000); // Refresh 30 minutes before expiry
        const timeUntilRefresh = refreshTime - Date.now();
        
        if (timeUntilRefresh > 0) {
            this.tokenRefreshTimer = setTimeout(() => {
                this.refreshVideoToken();
            }, timeUntilRefresh);
        }
    }
    
    async refreshVideoToken() {
        try {
            console.log('Refreshing video token...');
            const newVideoData = await this.loadVideoData();
            
            // Update video sources with new tokens
            const sources = [];
            
            if (newVideoData.video_urls.hls) {
                sources.push({
                    src: newVideoData.video_urls.hls,
                    type: 'application/x-mpegURL'
                });
            }
            
            if (newVideoData.video_urls.mp4) {
                sources.push({
                    src: newVideoData.video_urls.mp4,
                    type: 'video/mp4'
                });
            }
            
            // Update player sources without interrupting playback
            this.player.src(sources);
            
            // Setup next refresh
            this.setupTokenRefresh();
            
            console.log('Video token refreshed successfully');
            
        } catch (error) {
            console.error('Failed to refresh video token:', error);
            this.showError('Video session expired. Please refresh the page.');
        }
    }
    
    handlePlayerError(error) {
        console.error('Player error:', error);
        
        // Try to recover from common errors
        if (error && error.code) {
            switch (error.code) {
                case 1: // MEDIA_ERR_ABORTED
                    this.showError('Video loading was aborted. Please try again.');
                    break;
                case 2: // MEDIA_ERR_NETWORK
                    this.showError('Network error occurred. Please check your connection.');
                    break;
                case 3: // MEDIA_ERR_DECODE
                    this.showError('Video format not supported by your browser.');
                    break;
                case 4: // MEDIA_ERR_SRC_NOT_SUPPORTED
                    this.showError('Video source not available. Please try again later.');
                    break;
                default:
                    this.showError('An error occurred while playing the video.');
            }
        }
    }
    
    showError(message) {
        const container = document.getElementById(this.containerId);
        if (!container) return;
        
        const errorDiv = document.createElement('div');
        errorDiv.className = 'video-error-message';
        errorDiv.innerHTML = `
            <div class="error-content">
                <i class="fas fa-exclamation-triangle"></i>
                <p>${message}</p>
                <button class="btn btn-primary btn-sm" onclick="location.reload()">Refresh Page</button>
            </div>
        `;
        
        // Add error styles
        const style = document.createElement('style');
        style.textContent = `
            .video-error-message {
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: rgba(0, 0, 0, 0.9);
                color: white;
                padding: 30px;
                border-radius: 10px;
                text-align: center;
                z-index: 1000;
                max-width: 400px;
            }
            .error-content i {
                font-size: 2rem;
                color: #e74a3b;
                margin-bottom: 15px;
            }
            .error-content p {
                margin-bottom: 20px;
            }
        `;
        
        document.head.appendChild(style);
        
        container.style.position = 'relative';
        container.appendChild(errorDiv);
    }
    
    // Public methods
    play() {
        if (this.player) {
            return this.player.play();
        }
    }
    
    pause() {
        if (this.player) {
            this.player.pause();
        }
    }
    
    currentTime(time) {
        if (this.player) {
            if (time !== undefined) {
                this.player.currentTime(time);
            } else {
                return this.player.currentTime();
            }
        }
    }
    
    duration() {
        if (this.player) {
            return this.player.duration();
        }
    }
    
    volume(vol) {
        if (this.player) {
            if (vol !== undefined) {
                this.player.volume(vol);
            } else {
                return this.player.volume();
            }
        }
    }
    
    destroy() {
        if (this.tokenRefreshTimer) {
            clearTimeout(this.tokenRefreshTimer);
        }
        
        if (this.progressTracker) {
            this.progressTracker.destroy();
        }
        
        if (this.player) {
            this.player.dispose();
        }
    }
}

// Utility function to initialize Bunny video player
function initBunnyVideoPlayer(containerId, unitId, options = {}) {
    return new BunnyVideoPlayer(containerId, unitId, options);
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { BunnyVideoPlayer, initBunnyVideoPlayer };
}

// Global access
window.BunnyVideoPlayer = BunnyVideoPlayer;
window.initBunnyVideoPlayer = initBunnyVideoPlayer;