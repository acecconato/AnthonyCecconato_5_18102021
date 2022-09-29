<?php

declare(strict_types=1);

namespace Blog\Event\Dispatcher;

use Blog\DependencyInjection\ContainerInterface;
use Blog\Event\EventListener\EventListenerInterface;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use Psr\EventDispatcher\EventDispatcherInterface;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;

class EventDispatcher implements EventDispatcherInterface
{
    public function __construct(
        private readonly ListenerProviderInterface $listenerProvider,
        private readonly ContainerInterface $container
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function dispatch(object $event): object
    {
        if ($event instanceof StoppableEventInterface && $event->isPropagationStopped()) {
            return $event;
        }

        foreach ($this->listenerProvider->getListenersForEvent($event) as $eventListenerFqcn) {
            /**
             * @var EventListenerInterface $eventListener
            */
            $eventListener = $this->container->get($eventListenerFqcn);
            $eventListener->execute($event);
        }

        return $event;
    }
}
