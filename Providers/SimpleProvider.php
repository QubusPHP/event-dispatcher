<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Providers;

use Psr\EventDispatcher\ListenerProviderInterface;

class SimpleProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    public function listen(string $eventClass, callable $listener): void
    {
        $this->listeners[$eventClass][] = $listener;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->listeners[get_class($event)] ?? [] as $listener) {
            yield $listener;
        }
    }
}
