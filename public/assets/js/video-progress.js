/**
 * Video Progress Tracking System
 * Handles automatic progress tracking for video players
 */
class VideoProgressTracker {
    constructor(videoElement, unitId, options = {}) {
        this.video = videoElement;
        this.unitId = unitId;
        this.options = {
            updateInterval: 5000, // Update every 5 seconds
            completionThreshold: 0.9, // Consider completed at 90%
            autoMarkComplete: true,
            apiEndpoint: '/progress/update',
            ...options
        };
        
        this.lastUpdateTime = 0;
        this.totalWatchTime = 0;
        this.isTracking = false;
        this.progressData = {
            progress_percentage: 0,
            watch_time_seconds: 0,
            last_position_seconds: 0
        };
        
        this.init();
    }
    
    init() {
        if (!this.video || !this.unitId) {
            console.error('VideoProgressTracker: Video element or unit ID not provided');
            return;
        }
        
        // Load existing progress
        this.loadProgress();
        
        // Bind event listeners
        this.bindEvents();
        
        // Start tracking interval
        this.startTracking();
    }
    
    async loadProgress() {
        try {
            const response = await fetch(`/progress/unit-progress?unit_id=${this.unitId}`);
            const data = await response.json();
            
            if (data.success && data.progress) {
                this.progressData = {
                    progress_percentage: data.progress.progress_percentage || 0,
                    watch_time_seconds: data.progress.watch_time_seconds || 0,
                    last_position_seconds: data.progress.last_position_seconds || 0
                };
                
                // Resume from last position if video hasn't started
                if (this.video.currentTime === 0 && this.progressData.last_position_seconds > 0) {
                    this.video.currentTime = this.progressData.last_position_seconds;
                }
                
                // Update UI if callback provided
                if (this.options.onProgressLoaded) {
                    this.options.onProgressLoaded(this.progressData);
                }
            }
        } catch (error) {
            console.error('Failed to load progress:', error);
        }
    }
    
    bindEvents() {
        // Track play/pause for watch time calculation
        this.video.addEventListener('play', () => {
            this.lastUpdateTime = Date.now();
            this.isTracking = true;
        });
        
        this.video.addEventListener('pause', () => {
            this.updateWatchTime();
            this.isTracking = false;
        });
        
        // Track seeking
        this.video.addEventListener('seeked', () => {
            this.updateProgress(false); // Update position but don't increment watch time
        });
        
        // Handle video end
        this.video.addEventListener('ended', () => {
            this.updateWatchTime();
            this.isTracking = false;
            
            if (this.options.autoMarkComplete) {
                this.markCompleted();
            }
        });
        
        // Handle page unload
        window.addEventListener('beforeunload', () => {
            if (this.isTracking) {
                this.updateWatchTime();
                this.saveProgress(true); // Synchronous save
            }
        });
        
        // Handle visibility change (tab switching)
        document.addEventListener('visibilitychange', () => {
            if (document.hidden && this.isTracking) {
                this.updateWatchTime();
                this.isTracking = false;
            } else if (!document.hidden && !this.video.paused) {
                this.lastUpdateTime = Date.now();
                this.isTracking = true;
            }
        });
    }
    
    startTracking() {
        setInterval(() => {
            if (this.isTracking && !this.video.paused) {
                this.updateWatchTime();
                this.updateProgress();
            }
        }, this.options.updateInterval);
    }
    
    updateWatchTime() {
        if (this.lastUpdateTime > 0) {
            const elapsed = Math.floor((Date.now() - this.lastUpdateTime) / 1000);
            this.totalWatchTime += elapsed;
            this.progressData.watch_time_seconds += elapsed;
        }
        this.lastUpdateTime = Date.now();
    }
    
    updateProgress(saveToServer = true) {
        if (!this.video.duration || this.video.duration === 0) {
            return;
        }
        
        // Calculate progress percentage
        const progressPercentage = Math.min(100, (this.video.currentTime / this.video.duration) * 100);
        
        // Update progress data
        this.progressData.progress_percentage = Math.max(this.progressData.progress_percentage, progressPercentage);
        this.progressData.last_position_seconds = Math.floor(this.video.currentTime);
        
        // Check for completion
        const isCompleted = progressPercentage >= (this.options.completionThreshold * 100);
        
        if (saveToServer) {
            this.saveProgress();
        }
        
        // Trigger callbacks
        if (this.options.onProgressUpdate) {
            this.options.onProgressUpdate(this.progressData, isCompleted);
        }
        
        // Auto-complete if threshold reached
        if (isCompleted && this.options.autoMarkComplete) {
            this.markCompleted();
        }
    }
    
    async saveProgress(synchronous = false) {
        const data = {
            unit_id: this.unitId,
            progress_percentage: this.progressData.progress_percentage,
            watch_time_seconds: this.progressData.watch_time_seconds,
            last_position_seconds: this.progressData.last_position_seconds
        };
        
        try {
            if (synchronous) {
                // Use sendBeacon for synchronous requests (page unload)
                const formData = new FormData();
                Object.keys(data).forEach(key => formData.append(key, data[key]));
                navigator.sendBeacon(this.options.apiEndpoint, formData);
            } else {
                const response = await fetch(this.options.apiEndpoint, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify(data)
                });
                
                const result = await response.json();
                
                if (result.success && this.options.onProgressSaved) {
                    this.options.onProgressSaved(result);
                }
            }
        } catch (error) {
            console.error('Failed to save progress:', error);
            if (this.options.onError) {
                this.options.onError(error);
            }
        }
    }
    
    async markCompleted() {
        try {
            const response = await fetch('/progress/mark-completed', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({
                    unit_id: this.unitId
                })
            });
            
            const result = await response.json();
            
            if (result.success) {
                if (this.options.onUnitCompleted) {
                    this.options.onUnitCompleted(result);
                }
                
                // Show completion notification
                this.showCompletionNotification(result);
            }
        } catch (error) {
            console.error('Failed to mark unit as completed:', error);
        }
    }
    
    showCompletionNotification(result) {
        // Create completion notification
        const notification = document.createElement('div');
        notification.className = 'video-completion-notification';
        notification.innerHTML = `
            <div class="completion-content">
                <i class="fas fa-check-circle"></i>
                <h4>Unit Completed!</h4>
                <p>Great job! You've completed this unit.</p>
                ${result.next_unit ? `
                    <a href="${result.next_unit.url}" class="btn btn-primary btn-sm">
                        Next: ${result.next_unit.title}
                    </a>
                ` : ''}
                <button class="btn btn-secondary btn-sm" onclick="this.parentElement.parentElement.remove()">
                    Continue Watching
                </button>
            </div>
        `;
        
        // Add styles
        const style = document.createElement('style');
        style.textContent = `
            .video-completion-notification {
                position: fixed;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                background: white;
                border-radius: 10px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.3);
                z-index: 10000;
                padding: 0;
                max-width: 400px;
                width: 90%;
            }
            .completion-content {
                padding: 30px;
                text-align: center;
            }
            .completion-content i {
                font-size: 3rem;
                color: #28a745;
                margin-bottom: 15px;
            }
            .completion-content h4 {
                margin-bottom: 10px;
                color: #333;
            }
            .completion-content p {
                color: #666;
                margin-bottom: 20px;
            }
            .completion-content .btn {
                margin: 5px;
            }
        `;
        
        document.head.appendChild(style);
        document.body.appendChild(notification);
        
        // Auto-remove after 10 seconds
        setTimeout(() => {
            if (notification.parentElement) {
                notification.remove();
            }
        }, 10000);
    }
    
    // Public methods
    getCurrentProgress() {
        return this.progressData;
    }
    
    setProgress(percentage) {
        if (percentage >= 0 && percentage <= 100) {
            this.progressData.progress_percentage = percentage;
            this.saveProgress();
        }
    }
    
    destroy() {
        // Clean up event listeners and intervals
        this.isTracking = false;
        if (this.trackingInterval) {
            clearInterval(this.trackingInterval);
        }
    }
}

// Utility function to initialize progress tracking
function initVideoProgressTracking(videoSelector, unitId, options = {}) {
    const video = document.querySelector(videoSelector);
    if (video) {
        return new VideoProgressTracker(video, unitId, options);
    } else {
        console.error('Video element not found:', videoSelector);
        return null;
    }
}

// Export for module systems
if (typeof module !== 'undefined' && module.exports) {
    module.exports = { VideoProgressTracker, initVideoProgressTracking };
}

// Global access
window.VideoProgressTracker = VideoProgressTracker;
window.initVideoProgressTracking = initVideoProgressTracking;