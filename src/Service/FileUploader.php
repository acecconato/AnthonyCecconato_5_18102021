<?php

declare(strict_types=1);

namespace Blog\Service;

use Cocur\Slugify\Slugify;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(private string $targetDirectory)
    {
    }

    public function upload(UploadedFile $file)
    {
        dd($file);
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}