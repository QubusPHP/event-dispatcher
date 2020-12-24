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

namespace Qubus\EventDispatcher\ActionFilter\Traits;

use ReflectionClass;
use RuntimeException;

trait StaticProxy
{
    /**
     * The stored singleton instance.
     *
     * @var self $instance.
     */
    protected static $instance;

    /**
     * Creates the original or retrieves the stored singleton instance.
     *
     * @return self
     */
    public static function getInstance()
    {
        if (! static::$instance) {
            static::$instance = (new ReflectionClass(static::class))
                ->newInstanceWithoutConstructor();
        }

        return static::$instance;
    }

    /**
     * Reset the Container instance.
     */
    public static function resetInstance()
    {
        if (self::$instance) {
            self::$instance = null;
        }
    }

    /**
     * The constructor is disabled.
     *
     * @throws RuntimeException If called..
     */
    public function __construct()
    {
        throw new RuntimeException('You may not explicitly instantiate this object, because it is a singleton.');
    }

    /**
     * Cloning is disabled.
     *
     * @throws RuntimeException If called.
     */
    public function __clone()
    {
        throw new RuntimeException('You may not clone this object, because it is a singleton.');
    }

    /**
     * Wakeup is disabled.
     *
     * @throws RuntimeException If called.
     */
    public function __wakeup()
    {
        throw new RuntimeException('You may not wakeup this object, because it is a singleton.');
    }

    /**
     * Unserialization is disabled.
     *
     * @throws RuntimeException If called.
     */
    public function unserialize(array $serializedData)
    {
        throw new RuntimeException('You may not unserialize this object, because it is a singleton.');
    }
}
