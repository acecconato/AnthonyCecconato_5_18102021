<?php

declare(strict_types=1);

namespace Blog\DependencyInjection\Exceptions;

use Exception;
use Psr\Container\ContainerExceptionInterface;

class ContainerException extends Exception implements ContainerExceptionInterface
{
}
