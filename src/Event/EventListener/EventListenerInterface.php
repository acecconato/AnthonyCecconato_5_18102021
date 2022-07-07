<?php

namespace Blog\Event\EventListener;

use Psr\EventDispatcher\StoppableEventInterface;

interface EventListenerInterface
{
    public function execute(StoppableEventInterface $event): void;
}
