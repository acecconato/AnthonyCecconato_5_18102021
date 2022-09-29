<?php

declare(strict_types=1);

namespace Blog\Event;

class PreRequestHandlingEvent extends Event
{
    public function __construct(
        private readonly object $object
    ) {
    }

    public function getObject(): object
    {
        return $this->object;
    }
}
