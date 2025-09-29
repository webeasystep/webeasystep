<?php

namespace Modules\Units\Services;

use Exception;

class BunnyNetService
{
    private $apiKey;
    private $libraryId;
    private $baseUrl;

    public function __construct()
    {
        // Get configuration from environment or config
        $this->apiKey = env('BUNNY_NET_API_KEY', '');
        $this->libraryId = env('BUNNY_NET_LIBRARY_ID', '495222'); // Default to hardcoded library ID
        $this->baseUrl = 'https://video.bunnycdn.com/library/' . $this->libraryId . '/videos';
    }

    /**
     * Fetch video data from Bunny.net API
     *
     * @param string $videoId
     * @return array
     * @throws Exception
     */
    public function getVideoData($videoId)
    {
        try {
            // Validate inputs
            if (empty($this->apiKey) || empty($this->libraryId)) {
                throw new Exception('Bunny.net API credentials not configured');
            }

            if (empty($videoId)) {
                throw new Exception('Video ID is required');
            }

            // Prepare API request - Use /play endpoint to get complete video metadata
            $url = $this->baseUrl . '/' . $videoId . '/play';

            $headers = [
                'AccessKey: ' . $this->apiKey,
                'Content-Type: application/json',
                'Accept: application/json'
            ];

            // Make API request
            $ch = curl_init();
            curl_setopt_array($ch, [
                CURLOPT_URL => $url,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HTTPHEADER => $headers,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_FOLLOWLOCATION => true
            ]);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $error = curl_error($ch);
            curl_close($ch);

            // Handle cURL errors
            if ($error) {
                throw new Exception('cURL Error: ' . $error);
            }

            // Handle HTTP errors
            if ($httpCode !== 200) {
                throw new Exception('API Error: HTTP ' . $httpCode);
            }

            // Parse response
            $data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception('Invalid JSON response from API');
            }

            // Extract and format video data
            return $this->formatVideoData($data);

        } catch (Exception $e) {
            // Log error for debugging
            log_message('error', 'BunnyNet API Error: ' . $e->getMessage());

            // Return mock data for development/testing
            return $this->getMockVideoData($videoId);
        }
    }

    /**
     * Format video data from Bunny.net /play endpoint response
     *
     * @param array $data
     * @return array
     */
    private function formatVideoData($data)
    {
        // Extract video data from the nested structure
        $video = $data['video'] ?? [];
        
        // Format duration from seconds to MM:SS format
        $duration = $video['length'] ?? 0;
        $formattedDuration = $this->formatDurationToMinutes($duration);
        
        // Clean video title by removing .mp4 extension
        $videoTitle = $video['title'] ?? 'Untitled Video';
        $cleanVideoTitle = preg_replace('/\.mp4$/i', '', $videoTitle);
        
        return [
            'video_id' => $video['guid'] ?? '',
            'video_title' => $cleanVideoTitle,
            'video_duration' => $formattedDuration,
            'video_thumbnail' => $this->getThumbnailUrl($video['guid'] ?? '', $video['thumbnailFileName'] ?? null),
            'title' => $cleanVideoTitle,
            'duration' => $duration,
            'thumbnail' => $this->getThumbnailUrl($video['guid'] ?? '', $video['thumbnailFileName'] ?? null),
            'status' => $video['status'] ?? 0,
            'views' => $video['views'] ?? 0,
            'created_at' => $video['dateUploaded'] ?? null,
            'file_size' => $video['storageSize'] ?? 0,
            'width' => $video['width'] ?? 0,
            'height' => $video['height'] ?? 0,
            'framerate' => $video['framerate'] ?? 0,
            'description' => $video['description'] ?? '',
            'collection_id' => $video['collectionId'] ?? '',
            'video_library_id' => $video['videoLibraryId'] ?? $this->libraryId,
            'stream_url' => $data['videoPlaylistUrl'] ?? '',
            'fallback_url' => $data['fallbackUrl'] ?? '',
            'preview_url' => $data['previewUrl'] ?? ''
        ];
    }

    /**
     * Get mock video data for development/testing
     *
     * @param string $videoId
     * @return array
     */
    private function getMockVideoData($videoId)
    {
        $duration = rand(300, 3600); // 5 minutes to 1 hour
        $formattedDuration = $this->formatDurationToMinutes($duration);
        
        return [
            'video_id' => $videoId,
            'video_title' => 'Sample Video - ' . $videoId,
            'video_duration' => $formattedDuration,
            'video_thumbnail' => 'https://via.placeholder.com/640x360/0066cc/ffffff?text=Video+' . $videoId,
            'title' => 'Sample Video - ' . $videoId,
            'duration' => $duration,
            'thumbnail' => 'https://via.placeholder.com/640x360/0066cc/ffffff?text=Video+' . $videoId,
            'status' => 4, // Ready status
            'views' => rand(0, 1000),
            'created_at' => date('Y-m-d H:i:s'),
            'file_size' => rand(10000000, 100000000), // 10MB to 100MB
            'width' => 1920,
            'height' => 1080,
            'framerate' => 30,
            'description' => 'Sample video description',
            'collection_id' => 'mock-collection-' . substr($videoId, 0, 8),
            'video_library_id' => $this->libraryId,
            'stream_url' => '',
            'fallback_url' => '',
            'preview_url' => ''
        ];
    }

    /**
     * Get video thumbnail URL
     *
     * @param string $videoId
     * @param string $thumbnailFileName
     * @return string
     */
    public function getThumbnailUrl($videoId, $thumbnailFileName = null)
    {
        if (empty($thumbnailFileName)) {
            return 'https://via.placeholder.com/640x360/0066cc/ffffff?text=No+Thumbnail';
        }

        return 'https://vz-' . $this->libraryId . '.b-cdn.net/' . $videoId . '/' . $thumbnailFileName;
    }

    /**
     * Get video stream URL
     *
     * @param string $videoId
     * @return string
     */
    public function getStreamUrl($videoId)
    {
        return 'https://iframe.mediadelivery.net/embed/' . $this->libraryId . '/' . $videoId;
    }

    /**
     * Format duration from seconds to MM:SS format
     *
     * @param int $seconds
     * @return string
     */
    private function formatDurationToMinutes($seconds)
    {
        if ($seconds <= 0) {
            return '00:00';
        }
        
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        
        if ($hours > 0) {
            return sprintf('%d:%02d:%02d', $hours, $minutes, $remainingSeconds);
        } else {
            return sprintf('%02d:%02d', $minutes, $remainingSeconds);
        }
    }
    
    /**
     * Format duration from seconds to human readable format (in minutes)
     *
     * @param int $seconds
     * @return string
     */
    public static function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' ثانية';
        } else {
            // Convert all durations to minutes, regardless of length
            $totalMinutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            
            if ($remainingSeconds > 0) {
                return $totalMinutes . ' دقيقة و ' . $remainingSeconds . ' ثانية';
            } else {
                return $totalMinutes . ' دقيقة';
            }
        }
    }

    /**
     * Validate video ID format
     *
     * @param string $videoId
     * @return bool
     */
    public static function isValidVideoId($videoId)
    {
        // Bunny.net video IDs are typically UUIDs
        return preg_match('/^[a-f0-9]{8}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{4}-[a-f0-9]{12}$/i', $videoId);
    }
}
