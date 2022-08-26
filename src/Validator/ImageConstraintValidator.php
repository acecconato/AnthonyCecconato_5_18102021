<?php

declare(strict_types=1);

namespace Blog\Validator;

use File;
use Assert\InvalidArgumentException;
use Blog\Validator\Constraint\ConstraintInterface;
use Blog\Validator\Constraint\Image;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Mime\MimeTypes;

class ImageConstraintValidator implements ConstraintValidatorInterface
{
    /**
     * @param mixed $value
     * @param Image $constraint
     * @param string|null $propertyPath
     *
     * @return bool
     * @throws InvalidArgumentException
     */
    public function validate(mixed $value, ConstraintInterface $constraint, ?string $propertyPath = null): bool
    {
        if (! $value) {
            return true;
        }

        // @todo Check size

        // @phpstan-ignore-next-line
        if (! is_object($value) || $value::class !== File::class && $value::class !== UploadedFile::class) {
            throw new InvalidArgumentException($value . ' is not a file', 0, $propertyPath);
        }

        $mimeTypes    = new MimeTypes();
        $allowedTypes = ['jpg', 'png', 'svg', 'gif'];

        foreach ($allowedTypes as $type) {
            // @phpstan-ignore-next-line
            if (in_array($value->getMimeType(), $mimeTypes->getMimeTypes($type))) {
                return true;
            }
        }

        throw new InvalidArgumentException(
        // @phpstan-ignore-next-line
            sprintf($constraint->message ?: '%s is not a valid image', $value->getClientOriginalName()),
            0,
            $propertyPath
        );
    }
}
