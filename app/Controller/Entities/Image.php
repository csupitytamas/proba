<?php

namespace App\Controller\Entities;

use Exception;

class Image
{
    /**
     * @throws Exception
     */
    public function store($image): string
    {
        $uploadDir = 'app/img/';
        if (!is_null($image)) {
            if (is_array($image) && $image['error'] === UPLOAD_ERR_OK) {
                $originalFileData = pathinfo($image['name']);
                $fileName = basename($image['name']);
                $hashedFilename = hash('md5', $fileName . time()) .  '.' . $originalFileData['extension'];
                $targetFile = $uploadDir . $hashedFilename;
                if(!is_dir($uploadDir)) {
                    mkdir($uploadDir);
                }
                $uploaded = move_uploaded_file($image['tmp_name'], $targetFile);
                if (!$uploaded) {
                    throw new Exception('File upload failed.');
                }
                return $targetFile;
            }
            $error = $image['error'] ?? 'unknown error';
            throw new Exception('File upload error: ' . $this->fileUploadErrorExplanation($error));
        }
        return '';
    }


    /**
     * @param $error
     * @return string
     */
    private function fileUploadErrorExplanation($error): string
    {
        return match ($error) {
            UPLOAD_ERR_INI_SIZE => 'The uploaded file exceeds the upload_max_filesize directive in php.ini.',
            UPLOAD_ERR_FORM_SIZE => 'The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form.',
            UPLOAD_ERR_PARTIAL => 'The uploaded file was only partially uploaded.',
            UPLOAD_ERR_NO_FILE => 'No file was uploaded.',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing a temporary folder.',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk.',
            UPLOAD_ERR_EXTENSION => 'A PHP extension stopped the file upload.',
            default => 'Unknown upload error.',
        };
    }
}