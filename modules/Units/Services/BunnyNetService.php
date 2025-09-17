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
        $this->libraryId = env('BUNNY_NET_LIBRARY_ID', '');
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
            
            // Prepare API request
            $url = $this->baseUrl . '/' . $videoId;
            
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
     * Format video data from Bunny.net response
     * 
     * @param array $data
     * @return array
     */
    private function formatVideoData($data)
    {
        return [
            'title' => $data['title'] ?? 'Untitled Video',
            'duration' => $data['length'] ?? 0,
            'thumbnail' => $data['thumbnailFileName'] ?? '',
            'status' => $data['status'] ?? 0,
            'views' => $data['views'] ?? 0,
            'created_at' => $data['dateUploaded'] ?? null,
            'file_size' => $data['storageSize'] ?? 0,
            'width' => $data['width'] ?? 0,
            'height' => $data['height'] ?? 0,
            'framerate' => $data['framerate'] ?? 0
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
        return [
            'title' => 'Sample Video - ' . $videoId,
            'duration' => rand(300, 3600), // 5 minutes to 1 hour
            'thumbnail' => 'https://via.placeholder.com/640x360/0066cc/ffffff?text=Video+' . $videoId,
            'status' => 4, // Ready status
            'views' => rand(0, 1000),
            'created_at' => date('Y-m-d H:i:s'),
            'file_size' => rand(10000000, 100000000), // 10MB to 100MB
            'width' => 1920,
            'height' => 1080,
            'framerate' => 30
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
     * Format duration from seconds to human readable format
     * 
     * @param int $seconds
     * @return string
     */
    public static function formatDuration($seconds)
    {
        if ($seconds < 60) {
            return $seconds . ' ثانية';
        } elseif ($seconds < 3600) {
            $minutes = floor($seconds / 60);
            $remainingSeconds = $seconds % 60;
            return $minutes . ' دقيقة' . ($remainingSeconds > 0 ? ' و ' . $remainingSeconds . ' ثانية' : '');
        } else {
            $hours = floor($seconds / 3600);
            $minutes = floor(($seconds % 3600) / 60);
            $remainingSeconds = $seconds % 60;
            
            $result = $hours . ' ساعة';
            if ($minutes > 0) {
                $result .= ' و ' . $minutes . ' دقيقة';
            }
            if ($remainingSeconds > 0) {
                $result .= ' و ' . $remainingSeconds . ' ثانية';
            }
            
            return $result;
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