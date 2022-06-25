<?php

declare(strict_types=1);

namespace Blog\Event\EventListener;

use Blog\Event\PreRequestHandlingEvent;
use Symfony\Component\HttpFoundation\Request;

class RequestHandlingListener
{
    public function __invoke(PreRequestHandlingEvent $event): void
    {
        $object = $event->getObject();

        if (!$object instanceof Request) {
            return;
        }

        // @Todo Handle session and cookies to reconnect users
    }
}