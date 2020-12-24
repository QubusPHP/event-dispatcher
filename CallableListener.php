<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *
 * @since      1.0.0
 */

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use function call_user_func;

class CallableListener implements EventListener
{
    /**
     * The callable callback.
     *
     * @var callable $callable
     */
    protected $callable;

    /**
     * Array of callable-listeners.
     *
     * @var array $listeners
     */
    protected static $listeners = [];

    /**
     * @param callable $callable
     */
    public function __construct($callable)
    {
        $this->callable = $callable;
        static::$listeners[] = $this;
    }

    /**
     * Gets callback.
     *
     * @return callable
     */
    public function getCallable()
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event)
    {
        call_user_func($this->callable, $event);
    }

    /**
     * Creates a callable-listener.
     *
     * @param callable $callable
     */
    public static function createFromCallable($callable): CallableListener
    {
        return new static($callable);
    }

    /**
     * Finds the listener from the collection by its callable.
     *
     * @param callable $callable
     * @return CallableListener|false
     */
    public static function findByCallable($callable)
    {
        foreach (static::$listeners as $listener) {
            if ($listener->getCallable() === $callable) {
                return $listener;
            }
        }

        return false;
    }

    /**
     * Removes all registered callable-listeners.
     */
    public static function clearListeners()
    {
        static::$listeners = [];
    }
}
