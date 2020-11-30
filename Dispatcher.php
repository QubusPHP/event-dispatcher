<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use Qubus\Exception\Data\TypeException;

use function array_merge;
use function call_user_func;
use function is_array;
use function is_callable;
use function is_string;

class Dispatcher implements EventDispatcher
{
    /**
     * Array of listeners.
     *
     * @var ListenerPriorityQueue[] $listeners
     */
    protected $listeners = [];

    /**
     * {@inheritdoc}
     */
    public function dispatch($eventName, ?Event $event = null)
    {
        if ($eventName instanceof Event) {
            $event = $eventName;
        } elseif (null === $event) {
            $event = new GenericEvent($eventName, null);
        }

        if (isset($this->listeners[$event->getName()])) {
            foreach ($this->listeners[$event->getName()] as $listener) {
                if ($event->isPropagationStopped()) {
                    break;
                }
                call_user_func([$listener, 'handle'], $event);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(string $eventName, $listener, int $priority = self::PRIORITY_DEFAULT)
    {
        if (! isset($this->listeners[$eventName])) {
            $this->listeners[$eventName] = new ListenerPriorityQueue();
        }
        if (is_callable($listener)) {
            $listener = CallableListener::createFromCallable($listener);
        }
        if (! $listener instanceof EventListener) {
            throw new TypeException(
                'The listener should be an implementation of EventListener or a callable.'
            );
        }
        $this->listeners[$eventName]->insert($listener, $priority);
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(EventSubscriber $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $parameters) {
            if (is_string($parameters)) {
                $this->addListener($eventName, [$subscriber, $parameters]);
            } elseif (is_string($parameters[0])) {
                $this->addListener($eventName, [$subscriber, $parameters[0]], $parameters[1] ?? 0);
            } else {
                foreach ($parameters as $listener) {
                    $this->addListener($eventName, [$subscriber, $listener[0]], $listener[1] ?? 0);
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener(string $eventName, $listener)
    {
        if (empty($this->listeners[$eventName])) {
            return;
        }
        if (is_callable($listener) && false === ($listener = CallableListener::findByCallable($listener))) {
            return;
        }
        $this->listeners[$eventName]->detach($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(EventSubscriber $subscriber)
    {
        foreach ($subscriber->getSubscribedEvents() as $eventName => $parameters) {
            if (is_array($parameters) && is_array($parameters[0])) {
                foreach ($parameters as $listener) {
                    $this->removeListener($eventName, [$subscriber, $listener[0]]);
                }
            } else {
                $this->removeListener($eventName, [$subscriber, is_string($parameters) ? $parameters : $parameters[0]]);
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListeners(?string $eventName = null)
    {
        if (null !== $eventName && isset($this->listeners[$eventName])) {
            $this->listeners[$eventName]->clear();
        } else {
            foreach ($this->listeners as $queue) {
                $queue->clear();
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function hasListener(string $eventName, $listener): bool
    {
        if (! isset($this->listeners[$eventName])) {
            return false;
        }
        if (is_callable($listener)) {
            $listener = CallableListener::findByCallable($listener);
        }

        return $this->listeners[$eventName]->contains($listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners(?string $eventName = null): array
    {
        if (null !== $eventName) {
            return isset($this->listeners[$eventName]) ?
            $this->listeners[$eventName]->all() : [];
        } else {
            $listeners = [];
            foreach ($this->listeners as $queue) {
                $listeners = array_merge($listeners, $queue->all());
            }

            return $listeners;
        }
    }
}
