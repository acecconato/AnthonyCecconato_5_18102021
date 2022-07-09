<?php

use Blog\Event\EventListener\ListenerProvider;
use Blog\Event\EventListener\RequestHandlingListener;
use Blog\Event\PreRequestHandlingEvent;

return function (ListenerProvider $listenerProvider) {
    $listenerProvider->addListener(PreRequestHandlingEvent::class, RequestHandlingListener::class);
};
