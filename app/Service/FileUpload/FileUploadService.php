<?php

namespace App\Service\FileUpload;


use App\Helpers\Constants;
use Respect\Validation\Rules\Uploaded;

class FileUploadService
{
    public static function uploadFile($Files, $fileKey, $destinationPath, $allowedExtensions, $maxFileSize)
    {
        // Check if file is present in the request
        $files = $Files;
        if (empty($files[$fileKey])) {
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
                'message' => "No file uploaded for key '$fileKey'."
            ];
        }

        // Check if the upload process encountered an error
        $file = $files[$fileKey];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return [
                'error' => true,
                'code' => Constants::UPLOAD_IS_MISSING,
                'message' => 'File upload error: ' . $file->getError(),
            ];
        }

        try {
            $fileType = $file->getClientMediaType() ?? "Unknown";
            $fileSize = $file->getSize();
            $fileName = $file->getClientFilename();

            // Validate file type
            $extension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            if (!in_array($extension, $allowedExtensions)) {
                return [
                    'error' => true,
                    'code' => Constants::INSERT_FAILURE,
                    'message' => "Invalid file type. Allowed types are: " . implode(", ", $allowedExtensions) . "."
                ];
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                return [
                    'error' => true,
                    'code' => Constants::INSERT_FAILURE,
                    'message' => "File size exceeds the limit of " . ($maxFileSize / 1024 / 1024) . " MB."
                ];
            }

            // Generate destination filename
            $uniqueFileName = uniqid("upload_", true) . ".$extension";

            // Move the file
            $file->moveTo("$destinationPath/$uniqueFileName");

            return [
                'error' => false,
                'code' => Constants::INSERT_SUCCESS,
                'message' => 'File uploaded successfully.',
                'fileName' => $destinationPath . "/" . $uniqueFileName,
                'file_size' => $fileSize,
                'file_type' => $fileType
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' => Constants::INSERT_FAILURE,
                'message' => "File upload failed. Error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Delete an uploaded file.
     *
     * @param string $filePath Path to the file to delete
     * @return array Response with success or error information
     */
    public static function deleteFile($filePath)
    {
        try {
            if (file_exists($filePath)) {
                unlink($filePath); // Delete the file
                return [
                    'error' => false,
                    'code' => Constants::INSERT_FAILURE,
                    'message' => 'File deleted successfully.'
                ];
            } else {
                return [
                    'error' => true,
                    'code' =>Constants::INSERT_FAILURE,
                    'message' => 'File not found.'
                ];
            }
        } catch (\Exception $e) {
            return [
                'error' => true,
                'code' =>Constants::INSERT_FAILURE,
                'message' => "File deletion failed. Error: " . $e->getMessage()
            ];
        }
    }

    /**
     * Update an uploaded file by replacing it with a new one.
     *
     * @param $Files Uploaded files array
     * @param string $fileKey File key to update
     * @param string $currentFilePath Path of the current file to replace
     * @param string $destinationPath Directory to upload the new file
     * @param array $allowedExtensions Allowed file extensions
     * @param int $maxFileSize Maximum file size in bytes
     * @return array Response with success or error information
     */
    public static function updateFile($Files, $fileKey, $currentFilePath, $destinationPath, $allowedExtensions, $maxFileSize)
    {
        // Delete the current file first
        $deleteResponse = self::deleteFile($currentFilePath);
        if ($deleteResponse['error']) {
            return $deleteResponse; // Return error if file deletion failed
        }

        // Upload the new file
        return self::uploadFile($Files, $fileKey, $destinationPath, $allowedExtensions, $maxFileSize);
    }
}
