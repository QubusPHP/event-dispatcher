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

namespace Qubus\EventDispatcher\ActionFilter;

class Observer
{
    /**
     * list of instances
     *
     * @var array $instances
     */
    protected array $instances = [];

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
     * @return object|null
     */
    public function get(string $key)
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
