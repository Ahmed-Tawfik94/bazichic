<?php

namespace App\Service\FileUpload;

use App\Helpers\Constants;

class FileUploader
{
    public static function uploadFile($Files,$fileKey, $destinationPath, $allowedExtensions, $maxFileSize) {

        // Check if file is present in the request
        $files = $Files;
        if (empty($files[$fileKey])) {
            return ['error'=> true,
                'code'=>Constants::INSERT_FAILURE,
                "message" => "No file uploaded for key '$fileKey'."];
        }
        // Check if the upload process encountered an error
        $file = $files[$fileKey];
        if ($file->getError() !== UPLOAD_ERR_OK) {
            return [
                'error' => true,
                'code'=>Constants::UPLOAD_IS_MISSING,
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
                return ['error'=> true,
                    'code'=>Constants::INSERT_FAILURE,
                    "message"=>"Invalid file type. Allowed types are: " . implode(", ", $allowedExtensions) . "."];
            }

            // Validate file size
            if ($fileSize > $maxFileSize) {
                return ['error'=> true,
                    'code'=>Constants::INSERT_FAILURE,
                    'message'=> "File size exceeds the limit of " . ($maxFileSize / 1024 / 1024) . " MB."];
            }

            // Generate destination filename
            $uniqueFileName = uniqid("upload_", true) . ".$extension";


            // Move the file
            $file->moveTo("$destinationPath/$uniqueFileName");

            return [
                'error'=> false,
                'code'=>Constants::INSERT_SUCCESS,
                'message'=>'File uploaded successfully.',
                'fileName'=>$destinationPath."/".$uniqueFileName,
                'file_size'=>$fileSize,
                'file_type'=>$fileType
            ];
        } catch (\Exception $e) {
            return ['error'=> true,
                'code'=>Constants::INSERT_FAILURE,
                "message" => "File upload failed. Error: " . $e->getMessage()];
        }
    }
    public static function uploadLogo($fileInputName, $uploadDir = "images/") {
        // Ensure the upload directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        // Define fixed filename
        $targetFile = $uploadDir . "logo.png";

        // Allowed file types
        $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];

        // Check if file was uploaded
        if (!isset($_FILES[$fileInputName]) || $_FILES[$fileInputName]['error'] != UPLOAD_ERR_OK) {
            return "Error: No file uploaded or an error occurred.";
        }

        $fileTmpPath = $_FILES[$fileInputName]['tmp_name'];
        $fileType = mime_content_type($fileTmpPath);

        // Validate file type
        if (!in_array($fileType, $allowedTypes)) {
            return "Error: Only PNG and JPG images are allowed.";
        }

        // Resize and save the image
        if (!self::resizeAndSaveImage($fileTmpPath, $targetFile, 150, 150)) {
            return "Error: Failed to process the image.";
        }

        return "Success: Logo uploaded successfully.";
    }

    public static function resizeAndSaveImage($sourcePath, $destinationPath, $maxWidth, $maxHeight): bool
    {
        // Get image info
        $imageInfo = getimagesize($sourcePath);
        if (!$imageInfo) return false;

        $width = $imageInfo[0];
        $height = $imageInfo[1];
        $mime = $imageInfo['mime'];

        // Preserve aspect ratio
        $ratio = min($maxWidth / $width, $maxHeight / $height);
        $newWidth = round($width * $ratio);
        $newHeight = round($height * $ratio);

        // Create image from source
        switch ($mime) {
            case 'image/jpeg':
            case 'image/jpg':
                $srcImage = imagecreatefromjpeg($sourcePath);
                break;
            case 'image/png':
                $srcImage = imagecreatefrompng($sourcePath);
                break;
            default:
                return false;
        }

        // Create a blank true color image with new dimensions
        $dstImage = imagecreatetruecolor($newWidth, $newHeight);

        // Preserve transparency for PNG
        if ($mime == 'image/png') {
            imagealphablending($dstImage, false);
            imagesavealpha($dstImage, true);
            $transparent = imagecolorallocatealpha($dstImage, 255, 255, 255, 127);
            imagefilledrectangle($dstImage, 0, 0, $newWidth, $newHeight, $transparent);
        }

        // High-quality image resampling
        imagecopyresampled($dstImage, $srcImage, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

        // Save the image with appropriate quality settings
        $result = false;
        if ($mime == 'image/jpeg' || $mime == 'image/jpg') {
            $result = imagejpeg($dstImage, $destinationPath, 95); // High quality JPEG (95%)
        } elseif ($mime == 'image/png') {
            $result = imagepng($dstImage, $destinationPath, 0); // No compression for PNG
        }

        // Free memory
        imagedestroy($srcImage);
        imagedestroy($dstImage);

        return $result;
    }

}