<?php

namespace Blog\Service;

use JetBrains\PhpStorm\ArrayShape;

class Paginator
{
    /**
     * @param int $page
     * @param int $nbItem
     * @param int $maxPerPage
     * @return array<mixed>
     */
    #[ArrayShape([
        'itemsCount' => "int",
        'pagesCount' => "int",
        'page' => "int",
        'offset' => "int",
        'maxPerPage' => "int",
        'range' => "array"
    ])] public static function getPaginatingDatas(int $page, int $nbItem, int $maxPerPage): array
    {
        $page = ($page >= 0) ? $page : 0;
        $pagesCount = (int)ceil($nbItem / $maxPerPage);
        $range = range(min($page + 3, 0, $pagesCount), max(0, $page - 3, $pagesCount - 1));

        return [
            'itemsCount' => $nbItem,
            'pagesCount' => $pagesCount,
            'page' => $page,
            'offset' => (int)round($page * $maxPerPage),
            'maxPerPage' => (int)$maxPerPage,
            'range' => $range
        ];
    }
}
