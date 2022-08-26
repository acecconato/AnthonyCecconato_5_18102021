<?php

declare(strict_types=1);

namespace Blog\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class StripTagsFilter extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            new TwigFilter('striptags', [$this, 'striptags'])
        ];
    }

    public function striptags(string $value): string
    {
        $value = preg_replace('#<[^>]+>#', ' ', $value);
        $value = strip_tags($value);
        $value = str_replace('&nbsp;', '', $value);
        return $value;
    }
}
