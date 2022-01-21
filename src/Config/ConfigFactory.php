<?php

declare(strict_types=1);

namespace Blog\Config;

class ConfigFactory
{
    public static function getConfig(): array
    {
        $config = [];
        $config['routes'] = require_once dirname(__DIR__).'/../config/routes.php';

        return $config;
    }
}