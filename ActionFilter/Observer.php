<?php

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
     * @return object
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
