<?php

declare(strict_types=1);

namespace Blog\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SecureFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('secure', [$this, 'secure'])
        ];
    }

    public function secure(string $value): string
    {
        return preg_replace('#<script(.*?)>(.*?)</script>#is', '', $value);
    }
}