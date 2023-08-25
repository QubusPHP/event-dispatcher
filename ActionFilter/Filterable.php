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

interface Filterable
{
    /**
     * Adds a filter.
     *
     * @param string $hook      Hook name.
     * @param mixed  $callback  Function to execute.
     * @param int    $priority  Priority of the filter.
     * @param int    $arguments Number of arguments to accept.
     */
    public function addFilter(
        string $hook,
        $callback,
        int $priority = 10,
        int $arguments = 1
    ): BaseHooks;

    /**
     * Runs a filter.
     *
     * Filters should always return something. The first parameter will always be the default value.
     * You can add as many parameters as you'd like.
     *
     * @param array ...$args First argument will be the name of the hook, and the rest will be args for the hook.
     */
    public function applyFilter(...$args);

    /**
     * Removes a filter.
     *
     * @param string $hook     Hook name.
     * @param mixed  $callback Function to execute.
     * @param int    $priority Priority of the filter.
     */
    public function removeFilter(string $hook, $callback, int $priority = 20): void;
}
