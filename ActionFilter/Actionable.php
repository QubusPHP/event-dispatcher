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

namespace Qubus\EventDispatcher\ActionFilter;

interface Actionable
{
    /**
     * Adds an action.
     *
     * @param string $hook      Hook name.
     * @param mixed  $callback  Function to execute.
     * @param int    $priority  Priority of the action.
     * @param int    $arguments Number of arguments to accept.
     */
    public function addAction(
        string $hook,
        $callback,
        int $priority = 10,
        int $arguments = 1
    ): BaseHooks;

    /**
     * Runs an action.
     *
     * Actions never return anything. It is merely a way of executing code at a specific time in your code.
     * You can add as many parameters as you'd like.
     *
     * @param mixed ...$args First argument will be the name of the hook, and the rest will be args for the hook.
     */
    public function doAction(...$args): void;

    /**
     * Removes an action.
     *
     * @param string $hook     Hook name.
     * @param mixed  $callback Function to execute.
     * @param int    $priority Priority of the action.
     */
    public function removeAction(string $hook, $callback, int $priority = 10): void;
}
