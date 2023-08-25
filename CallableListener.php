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

use Qubus\Exception\Data\TypeException;

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
    protected static array $listeners = [];

    /**
     * @param callable $callable
     * @throws TypeException
     */
    public function __construct($callable)
    {
        if (!is_callable($callable)) {
            throw new TypeException('Parameter must be a callable.');
        }

        $this->callable = $callable;
        static::$listeners[] = $this;
    }

    /**
     * Gets callback.
     *
     * @return callable
     */
    public function getCallable(): callable
    {
        return $this->callable;
    }

    /**
     * {@inheritdoc}
     */
    public function handle(Event $event): void
    {
        call_user_func($this->callable, $event);
    }

    /**
     * Creates a callable-listener.
     *
     * @param callable $callable
     * @throws TypeException
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
     * @throws TypeException
     */
    public static function findByCallable($callable): CallableListener|false
    {
        if (!is_callable($callable)) {
            throw new TypeException('Parameter must be a callable.');
        }

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
    public static function clearListeners(): void
    {
        static::$listeners = [];
    }
}
