<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Providers;

use Psr\EventDispatcher\ListenerProviderInterface;

class PrioritizedProvider implements ListenerProviderInterface
{
    private array $listeners = [];

    public function getListenersForEvent(object $event): iterable
    {
        $priorities = array_keys($this->listeners);
        usort($priorities, function ($a, $b) {
            return $b <=> $a;
        });

        foreach ($priorities as $priority) {
            foreach ($this->listeners[$priority] as $eventName => $listeners) {
                if ($event instanceof $eventName) {
                    foreach ($listeners as $listener) {
                        yield $listener;
                    }
                }
            }
        }
    }

    public function listen(string $eventType, callable $listener, int $priority = 1): void
    {
        $priority = sprintf('%d.0', $priority);
        if (
                isset($this->listeners[$priority][$eventType])
                && in_array($listener, $this->listeners[$priority][$eventType], true)
        ) {
            // Duplicate detected
            return;
        }
        $this->listeners[$priority][$eventType][] = $listener;
    }
}
