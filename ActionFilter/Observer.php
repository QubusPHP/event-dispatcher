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

namespace Qubus\EventDispatcher\ActionFilter;

use ReflectionException;

/**
 * @property-read Action $action
 * @property-read Filter $filter
 */
class Observer
{
    /**
     * list of instances
     *
     * @var array $instances
     */
    protected array $instances = [];

    /**
     * @throws ReflectionException
     */
    public function __construct()
    {
        $this->add('action', Action::getInstance());
        $this->add('filter', Filter::getInstance());
    }

    /**
     * @param object $value
     */
    public function add(string $key, $value): void
    {
        $this->instances[$key] = $value;
    }

    /**
     * @param string $key
     * @return object|null
     */
    public function get(string $key): ?object
    {
        if (isset($this->instances[$key])) {
            return $this->instances[$key];
        }

        return null;
    }

    /**
     * @return object
     */
    public function __get(string $key)
    {
        return $this->get($key);
    }
}
