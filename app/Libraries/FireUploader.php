<?php

namespace App\Libraries;

use CodeIgniter\Model;
use Config\Services;
use ReflectionClass;

class FireUploader
{
    /**
     * Processes the uploaded files
     * @param Model $model
     * @param string $field
     * @param $id
     */
    public function upload_photos(Model $model, string $field, $id): void
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
    }

    /**
     * Processes the hidden files
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
     * @param array $fileDataArray
     * @param $key
     * @param $fileData
     */
    private function removeFile(array &$fileDataArray, $key, $fileData): void
    {
        $fullPath = realpath($fileData['full_path']);

        if (!empty($fullPath) && !is_dir($fullPath) && file_exists($fullPath)) {
            unlink($fullPath);
        }

        unset($fileDataArray[$key]);
    }

    /**
     * Processes the uploaded files
     * @param $request
     * @param string $field
     * @param $module
     * @param array $fileDataArray
     */
    private function processUploadedFiles($request, string $field, array &$fileDataArray): void
    {
        $uploadedFiles = $request->getFiles()[$field];

        if (!empty($uploadedFiles)) {
            if (is_array($uploadedFiles)) {
                foreach ($uploadedFiles as $uploadedFile) {
                    $this->processFile($uploadedFile ,$fileDataArray);
                }
            } else {
                $this->processFile($uploadedFiles,$fileDataArray);
            }
        }
    }

    /**
     * Processes a file
     * @param $uploadedFile
     * @param $folder
     * @param array $fileDataArray
     */
    private function processFile($uploadedFile,array &$fileDataArray): void
    {
        [$realPath,$urlPath] = $this->getCurrentPath();

        if ($uploadedFile->isValid() && !$uploadedFile->hasMoved()) {
            foreach ($fileDataArray as &$fileData) {
                if ($fileData['original_name'] === $uploadedFile->getName() && empty($fileData['full_path'])) {
                    $randomName = $uploadedFile->getRandomName();
                    $fileData['full_path'] = $urlPath.'/'.$randomName;
                    $fileData['encoded_name'] = $randomName;

                    $uploadedFile->move($realPath, $randomName, true);
                }
            }
        }
    }

    /**
     * Creates JSON data
     * @param array $fileDataArray
     * @param string $field
     * @return array
     */
    private function createJsonData(array $fileDataArray, string $field): array
    {
        return [
            $field => json_encode([
                'fileCount' => count($fileDataArray),
                'files' => $fileDataArray
            ])
        ];
    }

    public function getCurrentPath(): array
    {

        $module = $this->getCurrentModuleName();

        if (!$module) {
            throw new \RuntimeException('Failed to detect the current module.');
        }
        // Get the current date
        $currentDate = date('d_m_Y');

        // Create the module-based directory structure if it does not exist
        $realPath = FCPATH . 'uploads' .DIRECTORY_SEPARATOR. $module;
        if (!is_dir($realPath)) {
            mkdir($realPath, 0775, true);
        }

        // Create the date-based directory structure if it does not exist
        $realPath = $realPath . DIRECTORY_SEPARATOR . $currentDate;

        if (!is_dir($realPath)) {
            mkdir($realPath, 0775, true);
        }
        // Store the directory path in a separate variable
         $urlPath = 'uploads/'. $module .'/'. $currentDate;

        return [$realPath,$urlPath];
    }

    /**
     * Gets the current module name
     * @return string|null
     */
    public function getCurrentModuleName(): ?string
    {
        $backtrace = debug_backtrace(DEBUG_BACKTRACE_IGNORE_ARGS, 6);
        $callerClass = $backtrace[5]['class'];

        $reflection = new ReflectionClass($callerClass);
        $namespaceParts = explode('\\', $reflection->getNamespaceName());

        return strtolower($namespaceParts[array_search('Modules', $namespaceParts) + 1] ?? null);
    }
}
