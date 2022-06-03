<?php

declare(strict_types=1);

namespace Blog\Service;

use DateTime;
use Exception;

abstract class Tools
{
    /**
     * @throws Exception
     */
    public static function now(): DateTime
    {
        return new DateTime('now');
    }
}