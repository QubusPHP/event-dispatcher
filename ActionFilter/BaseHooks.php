<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <josh@joshuaparker.blog>
 * @copyright  2018 Filip Štamcar (original author Tor Morten Jensen)
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *
 * @since      1.0.0
 */

declare(strict_types=1);

namespace Qubus\EventDispatcher\ActionFilter;

use Qubus\Exception\Exception;
use Qubus\Inheritance\SortCallback;
use stdClass;

use function array_slice;
use function array_values;
use function count;
use function is_callable;
use function key;
use function reset;
use function usort;

abstract class BaseHooks
{
    use SortCallback;

    /**
     * Default priority.
     *
     * @var int PRIORITY_NEUTRAL
     */
    public const PRIORITY_NEUTRAL = 10;

    /**
     * Default arguments accepted.
     *
     * @var int ARGUMENT_NEUTRAL
     */
    public const ARGUMENT_NEUTRAL = 1;

    /**
     * Holds the event hooks.
     *
     * @var array $hooks
     */
    protected ?array $hooks = [];

    /**
     * Adds a hook.
     *
     * @param string $hook      Hook name.
     * @param mixed  $callback  Function to execute.
     * @param int    $priority  Priority of the action.
     * @param int    $arguments Number of arguments to accept.
     */
    public function listen(
        string $hook,
        $callback,
        int $priority = self::PRIORITY_NEUTRAL,
        int $arguments = self::ARGUMENT_NEUTRAL
    ): BaseHooks {
        $this->hooks[] = [
            'hook'      => $hook,
            'callback'  => $callback,
            'priority'  => $priority,
            'arguments' => $arguments,
        ];

        return $this;
    }

    /**
     * Removes a hook.
     *
     * @param string $hook     Hook name.
     * @param mixed  $callback Function to execute.
     * @param int    $priority Priority of the action.
     */
    public function remove(string $hook, $callback, int $priority = self::PRIORITY_NEUTRAL): void
    {
        if ($this->hooks) {
            $hooks = $this->hooks;
            foreach ($this->hooks as $key => $value) {
                if (
                    $value['hook'] === $hook &&
                    $value['callback'] === $callback &&
                    $value['priority'] === $priority
                ) {
                    unset($hooks[$key]);
                }
            }
            $this->hooks = $hooks;
        }
    }

    /**
     * Remove all hooks with given hook in collection. If no hook, clear all hooks.
     *
     * @param string $hook Hook name.
     */
    public function removeAll(?string $hook = null): void
    {
        if ($this->hooks) {
            if ($hook) {
                $hooks = $this->hooks;
                foreach ($this->hooks as $key => $value) {
                    if ($value['hook'] === $hook) {
                        unset($hooks[$key]);
                    }
                }
                $this->hooks = $hooks;
            } else {
                $this->hooks = [];
            }
        }
    }

    /**
     * Gets a sorted list of all hooks.
     *
     * @return array
     */
    public function getHooks(): array
    {
        $hooks = $this->hooks;

        if (count($hooks) === 0) {
            return [];
        }

        reset($hooks);
        $key = key($hooks);

        $same = true;
        $previous = $hooks[$key];
        foreach ($hooks as $hook) {
            if ($previous['priority'] !== $hook['priority']) {
                $same = false;
                break;
            }
        }

        if ($same) {
            return $hooks;
        }

        usort($hooks, [$this, 'afsort']);

        return $hooks;
    }

    /**
     * Gets the function.
     *
     * @param string|callable $callback Callback.
     * @return callable A closure
     * @throws Exception
     */
    protected function getFunction($callback): callable
    {
        if (is_callable($callback)) {
            return $callback;
        }

        throw new Exception('$callback is not a Callable.');
    }

    /**
     * Figures out the hook.
     *
     * Will return an object with two keys. One for the name and one for the arguments that will be
     * passed to the hook itself.
     *
     * @param mixed $args
     */
    protected function createHook($args): stdClass
    {
        return (object) [
            'name' => $args[0],
            'args' => array_values(array_slice($args, 1)),
        ];
    }

    /**
     * Fires a new action/filter.
     *
     * @param string $action Name of action
     * @param mixed  $args   Arguments passed to the action
     */
    abstract protected function trigger(string $action, $args);
}
