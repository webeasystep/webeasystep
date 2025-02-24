<?php

namespace App\Libraries;

use CodeIgniter\Model;
use Config\Services;
use ReflectionClass;
use CodeIgniter\Database\BaseConnection;

class FireUploader
{
    /**
     * Database connection
     */
    protected BaseConnection $db;

    /**
     * Array to store thumbnail sizes and options
     */
    protected array $thumbSizes = [];

    public function __construct()
    {
        // Initialize the database connection
        $this->db = \Config\Database::connect();
    }

    /**
     * Adds a thumbnail size to the list with optional aspect ratio control
     *
     * @param int  $width
     * @param int  $height
     * @param bool $maintainAspectRatio
     * @return self
     */
    public function thumb(int $width, int $height, bool $maintainAspectRatio = true): self
    {
        $this->thumbSizes[] = [
            'width' => $width,
            'height' => $height,
            'maintainAspectRatio' => $maintainAspectRatio
        ];
        return $this;
    }

    /**
     * Processes the uploaded photos
     *
     * @param Model  $model
     * @param string $field
     * @param mixed  $id
     * @return self
     */
    public function upload_photos(Model $model, string $field, $id): self
    {
        $request = Services::request();

        // Process hidden files
        $fileDataArray = $this->processHiddenFiles($request, $field);

        // Sort array by order attribute
        $this->sortArrayByOrder($fileDataArray);

        // Process files based on action attribute
        $this->processFilesBasedOnAction($fileDataArray);

        // Re-index the array keys
        $fileDataArray = array_values($fileDataArray);

        // Process uploaded files
        $this->processUploadedFiles($request, $field, $fileDataArray);

        // Create JSON data
        $jsonData = $this->createJsonData($fileDataArray, $field);

        // Update the model
        $model->update($id, $jsonData);

        return $this;
    }

    /**
     * Processes the hidden files
     *
     * @param $request
     * @param string $field
     * @return array
     */
    private function processHiddenFiles($request, string $field): array
    {
        $fileDataArray = [];
        $hiddenFiles = $request->getPost($field);

        if (!empty($hiddenFiles)) {
            $hiddenFiles = is_array($hiddenFiles) ? $hiddenFiles : json_decode($hiddenFiles, true);
            if (is_array($hiddenFiles) && count($hiddenFiles) > 0) {
                foreach ($hiddenFiles as $hiddenFile) {
                    $json_file = json_decode($hiddenFile, true);
                    $fileDataArray[] = $json_file;
                }
            }
        }

        return $fileDataArray;
    }

    /**
     * Sorts an array by the order attribute
     *
     * @param array $fileDataArray
     */
    private function sortArrayByOrder(array &$fileDataArray): void
    {
        usort($fileDataArray, function ($a, $b) {
            return $a['order'] - $b['order'];
        });
    }

    /**
     * Processes files based on the action attribute
     *
     * @param array $fileDataArray
     */
    private function processFilesBasedOnAction(array &$fileDataArray): void
    {
        foreach ($fileDataArray as $key => $fileData) {
            switch ($fileData['action']) {
                case 'removed':
                    $this->removeFile($fileDataArray, $key, $fileData);
                    break;

                case 'added':
                case 'preloaded':
                    unset($fileDataArray[$key]['action']);
                    break;
            }
        }
    }

    /**
     * Removes a file based on the full_path attribute
     *
     * @param array $fileDataArray
     * @param mixed $key
     * @param mixed $fileData
     */
    private function removeFile(array &$fileDataArray, $key, $fileData): void
    {
        $fullPath = realpath(FCPATH . $fileData['full_path']);

        if (!empty($fullPath) && !is_dir($fullPath) && file_exists($fullPath)) {
            unlink($fullPath);

            // Also remove thumbnails
            if (isset($fileData['thumbnails']) && is_array($fileData['thumbnails'])) {
                foreach ($fileData['thumbnails'] as $thumbPath) {
                    $thumbFullPath = realpath(FCPATH . $thumbPath);
                    if (!empty($thumbFullPath) && file_exists($thumbFullPath)) {
                        unlink($thumbFullPath);
                    }
                }
            }
        }

        unset($fileDataArray[$key]);
    }

    /**
     * Processes the uploaded files
     *
     * @param $request
     * @param string $field
     * @param array  $fileDataArray
     */
    private function processUploadedFiles($request, string $field, array &$fileDataArray): void
    {
        $uploadedFiles = $request->getFiles()[$field] ?? null;

        if (!empty($uploadedFiles)) {
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $uploadedFile) {
                    $this->processFile($uploadedFile, $fileDataArray);
                }
            } else {
                $this->processFile($uploadedFiles, $fileDataArray);
            }
        }
    }

    /**
     * Processes a single uploaded file
     *
     * @param $uploadedFile
     * @param array $fileDataArray
     */
    private function processFile($uploadedFile, array &$fileDataArray): void
    {
        [$realPath, $urlPath] = $this->getCurrentPath();

        if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            foreach ($fileDataArray as &$fileData) {
                if ($fileData['original_name'] === $uploadedFile->getName() && empty($fileData['full_path'])) {
                    $randomName = $uploadedFile->getRandomName();
                    $fileData['full_path'] = $urlPath . '/' . $randomName;
                    $fileData['encoded_name'] = $randomName;

                    $uploadedFile->move($realPath, $randomName, true);

                    // Full path to the uploaded file
                    $uploadedFilePath = $realPath . DIRECTORY_SEPARATOR . $randomName;

                    // Generate thumbnails and store their paths
                    $thumbnailPaths = $this->generateThumbnails($uploadedFilePath, $realPath, $randomName, $urlPath);

                    // Include thumbnail paths in the file data
                    $fileData['thumbnails'] = $thumbnailPaths;
                }
            }
        }
    }

    /**
     * Generates thumbnails for an image and returns their paths
     *
     * @param string $sourceImagePath
     * @param string $destinationDirectory
     * @param string $filename
     * @param string $urlPath
     * @return array
     */
    private function generateThumbnails(string $sourceImagePath, string $destinationDirectory, string $filename, string $urlPath): array
    {
        // Get the image service
        $imageService = \Config\Services::image();

        $thumbnailPaths = [];

        foreach ($this->thumbSizes as $size) {
            $width = $size['width'];
            $height = $size['height'];
            $maintainAspectRatio = $size['maintainAspectRatio'];

            // Create the thumbnail directory if it doesn't exist
            $thumbDirName = $width . 'x' . $height;
            $thumbDirPath = $destinationDirectory . DIRECTORY_SEPARATOR . $thumbDirName;

            if (!is_dir($thumbDirPath)) {
                mkdir($thumbDirPath, 0775, true);
            }

            // Set the full path for the thumbnail image
            $thumbImagePath = $thumbDirPath . DIRECTORY_SEPARATOR . $filename;

            // Resize the image and save it
            $imageService->withFile($sourceImagePath)
                ->resize($width, $height, $maintainAspectRatio)
                ->save($thumbImagePath);

            // Store the thumbnail path relative to the public directory
            $thumbnailPaths["{$width}x{$height}"] = $urlPath . '/' . $thumbDirName . '/' . $filename;
        }

        return $thumbnailPaths;
    }

    /**
     * Creates JSON data for the files
     *
     * @param array  $fileDataArray
     * @param string $field
     * @return array
     */
    private function createJsonData(array $fileDataArray, string $field): array
    {
        return [
            $field => json_encode([
                'fileCount' => count($fileDataArray),
                'files'     => $fileDataArray
            ])
        ];
    }

    /**
     * Retrieves the current path for uploads
     *
     * @return array
     */
    public function getCurrentPath(): array
    {
        $module = $this->getCurrentModuleName();

        if (!$module) {
            throw new \RuntimeException('Failed to detect the current module.');
        }
        // Get the current date
        $currentDate = date('d_m_Y');

        // Create the module-based directory structure if it does not exist
        $realPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . $module;
        if (!is_dir($realPath)) {
            mkdir($realPath, 0775, true);
        }

        // Create the date-based directory structure if it does not exist
        $realPath = $realPath . DIRECTORY_SEPARATOR . $currentDate;

        if (!is_dir($realPath)) {
            mkdir($realPath, 0775, true);
        }
        // Store the directory path in a separate variable
        $urlPath = 'uploads/' . $module . '/' . $currentDate;

        return [$realPath, $urlPath];
    }

    /**
     * Gets the current module name
     *
     * @return string|null
     */
    public function getCurrentModuleName(): ?string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);
        $callerClass = $backtrace[5]['class'] ?? null;

        if (!$callerClass) {
            return null;
        }

        $reflection     = new ReflectionClass($callerClass);
        $namespaceParts = explode('\\', $reflection->getNamespaceName());

        $modulesIndex = array_search('Modules', $namespaceParts);
        if ($modulesIndex !== false && isset($namespaceParts[$modulesIndex + 1])) {
            return strtolower($namespaceParts[$modulesIndex + 1]);
        }

        return null;
    }
}
