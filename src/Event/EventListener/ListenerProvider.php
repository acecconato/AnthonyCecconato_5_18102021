<?php

declare(strict_types=1);

namespace Blog\Event\EventListener;

use Psr\EventDispatcher\ListenerProviderInterface;

class ListenerProvider implements ListenerProviderInterface
{
    /** @var array<callable> */
    private array $listeners = [];

    /**
     * @param object $event
     * @return array<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        $eventType = $event::class;
        if (array_key_exists($eventType, $this->listeners)) {
            return $this->listeners[$eventType];
        }

        return [];
    }

    public function addListener(string $eventType, callable $callable): ListenerProvider
    {
        $this->listeners[$eventType][] = $callable;
        return $this;
    }

    public function clearListeners(string $eventType): ListenerProvider
    {
        if (array_key_exists($eventType, $this->listeners)) {
            unset($this->listeners[$eventType]);
        }

        return $this;
    }
}
