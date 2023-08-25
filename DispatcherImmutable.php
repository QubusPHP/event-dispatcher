<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <joshua@joshuaparker.dev>
 * @copyright  2018 Filip Štamcar (original author Tor Morten Jensen)
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use BadMethodCallException;
use Qubus\EventDispatcher\EventDispatcher;
use Qubus\Exception\Data\TypeException;

final class DispatcherImmutable implements EventDispatcher
{
    public function __construct(private EventDispatcher $dispatcher)
    {
    }

    /**
     * {@inheritdoc}
     * @throws TypeException
     */
    public function dispatch($eventName, ?Event $event = null)
    {
        if ($eventName === null) {
            throw new TypeException('Event name must be a string or implement Event.');
        }

        return $this->dispatcher->dispatch($eventName, $event);
    }

    /**
     * {@inheritdoc}
     */
    public function addListener(
        string $eventName,
        $listener,
        int $priority = self::PRIORITY_DEFAULT
    ) {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function addSubscriber(EventSubscriber $subscriber)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeListener(string $eventName, $listener)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeSubscriber(EventSubscriber $subscriber)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function removeAllListeners(?string $eventName = null)
    {
        throw new BadMethodCallException('Unmodifiable event dispatchers must not be modified.');
    }

    /**
     * {@inheritdoc}
     */
    public function hasListener(string $eventName, $listener): bool
    {
        return $this->dispatcher->hasListener($eventName, $listener);
    }

    /**
     * {@inheritdoc}
     */
    public function getListeners(?string $eventName = null): array
    {
        return $this->dispatcher->getListeners($eventName);
    }
}
