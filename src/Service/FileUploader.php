<?php

declare(strict_types=1);

namespace Blog\Service;

use Ramsey\Uuid\Rfc4122\UuidV4;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private readonly string $uploadDir
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $fileName         = UuidV4::uuid4() . '.' . $file->guessExtension();

        $file->move($this->getTargetDirectory(), $fileName);

        return $fileName;
    }

    public function replace(string $pathToFileFromUploadDir, UploadedFile $file): string
    {
        unlink($this->uploadDir . '/' . $pathToFileFromUploadDir);

        return $this->upload($file);
    }

    public function getTargetDirectory(): string
    {
        return $this->uploadDir;
    }

    public function remove(string $pathToFileFromUploadDir): bool
    {
        return unlink($this->uploadDir . '/' . $pathToFileFromUploadDir);
    }
}
