<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeExtensionGuesser;
use Symfony\Component\HttpFoundation\File\MimeType\MimeTypeGuesser;

/**
 * @author Dawid Góra
 */
class Base64Service
{
    /** @var string */
    private $filePrefix;

    /**
     * @param string $base64
     * @param string $targetPath
     * @param string $filePrefix
     * @return string name of converted file
     */
    public function convertToFile(string $base64, string $targetPath, $filePrefix = 'file_'): string
    {
        $this->filePrefix = $filePrefix;
        $fileName = $this->generateFileName();
        $filePath = $this->generateFilePath($targetPath, $fileName);

        $file = fopen($filePath, 'wb');
        /*
        $data = explode(',', $base64);
        fwrite($file, base64_decode($data[1]));
        */
        fwrite($file, base64_decode($base64));
        fclose($file);

        $fileExt = $this->getFileExt($filePath);
        rename($filePath, $filePath . '.' . $fileExt);

        return $fileName . '.' . $fileExt;
    }

    /**
     * @param string $targetPath
     * @param string $fileName
     * @return string
     */
    private function generateFilePath(string $targetPath, string $fileName): string
    {
        return $targetPath . '/' . $fileName;
    }

    /**
     * @return string
     */
    private function generateFileName(): string
    {
        return uniqid($this->filePrefix, true);
    }

    /**
     * @param string $filePath
     * @return string
     */
    private function getFileExt(string $filePath): string
    {
        $guesser = MimeTypeGuesser::getInstance();
        $extensionGuesser = new MimeTypeExtensionGuesser();

        return $extensionGuesser->guess(
            $guesser->guess($filePath)
        );
    }
}