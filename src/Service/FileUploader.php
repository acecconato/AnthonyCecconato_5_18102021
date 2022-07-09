<?php

declare(strict_types=1);

namespace Blog\Service;

use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private string $uploadDir
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $slugify = Slugify::create();

        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $slugify->slugify($originalFilename);
        $fileName = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function replace(string $oldFilename, UploadedFile $file): string
    {
        unlink($this->uploadDir . '/' . $oldFilename);
        return $this->upload($file);
    }

    public function getTargetDirectory(): string
    {
        return $this->uploadDir;
    }
}