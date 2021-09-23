<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <josh@joshuaparker.blog>
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *
 * @since      1.0.0
 */

declare(strict_types=1);

namespace Qubus\EventDispatcher;

interface EventDispatcher
{
    /**
     * Low priority.
     *
     * @var int PRIORITY_LOW
     */
    public const PRIORITY_LOW = -100;

    /**
     * Default priority.
     *
     * @var int PRIORITY_DEFAULT
     */
    public const PRIORITY_DEFAULT = 0;

    /**
     * High priority.
     *
     * @var int PRIORITY_HIGH
     */
    public const PRIORITY_HIGH = 100;

    /**
     * Dispatches an event to all registered listeners.
     *
     * @param string|Event $eventName
     */
    public function dispatch($eventName, ?Event $event = null);

    /**
     * Registries a listener for the event.
     *
     * @param EventListener|callable $listener
     */
    public function addListener(string $eventName, $listener, int $priority = self::PRIORITY_DEFAULT);

    /**
     * Registries a subscriber.
     */
    public function addSubscriber(EventSubscriber $subscriber);

    /**
     * Removes a listener from the specified event.
     *
     * @param EventListener|callable $listener
     */
    public function removeListener(string $eventName, $listener);

    /**
     * Removes a subscriber.
     */
    public function removeSubscriber(EventSubscriber $subscriber);

    /**
     * Removes all listeners from the specified event.
     */
    public function removeAllListeners(?string $eventName = null);

    /**
     * Checks whether the listener is existed for the event.
     *
     * @param EventListener|callable $listener
     */
    public function hasListener(string $eventName, $listener): bool;

    /**
     * Gets all listeners of the event or all registered listeners.
     *
     * @return array
     */
    public function getListeners(?string $eventName = null): array;
}
