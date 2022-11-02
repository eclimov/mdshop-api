<?php

namespace App\Service;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

// https://digitalfortress.tech/php/file-upload-with-api-platform-and-symfony/
class FileManager
{
    private string $uploadPath;
    private SluggerInterface $slugger;

    public function __construct(string $uploadPath, SluggerInterface $slugger)
    {
        $this->uploadPath = $uploadPath;
        $this->slugger = $slugger;
    }

    public function upload(UploadedFile $file, string $idName, string $relativePath)
    {
        $safeFilename = $this->slugger->slug($idName);
        $fileName = $safeFilename.'.'.$file->guessExtension();

        $file->move(
            $this->uploadPath.'/'.$relativePath.'/',
            $fileName
        );

        return $fileName;
    }

    public function remove(string $relativeFilePath)
    {
        $filePath = $this->uploadPath.'/'.$relativeFilePath;

        $fileSystem = new Filesystem();
        if ($fileSystem->exists($filePath)) {
            $fileSystem->remove($filePath);
        }
    }
}
