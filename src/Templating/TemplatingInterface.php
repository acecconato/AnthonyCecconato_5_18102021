<?php

declare(strict_types=1);

namespace Blog\Templating;

interface TemplatingInterface
{
    /** Render a template from a view.
     *
     * @param string $view
     * @param array<string> $context
     * @param bool $mainRequest
     *
     * @return string
     */
    public function render(string $view, array $context = [], bool $mainRequest = true): string;
}
