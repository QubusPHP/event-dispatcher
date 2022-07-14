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

use Qubus\Inheritance\StaticProxyAware;

use function call_user_func_array;
use function func_get_args;

final class Filter extends BaseHooks implements Filterable, RemoveAllFilters
{
    use StaticProxyAware;

    /**
     * Holds the value of the filter.
     *
     * @var mixed $value
     */
    protected mixed $value;

    /**
     * {@inheritDoc}
     */
    public function addFilter(
        string $hook,
        $callback,
        int $priority = self::PRIORITY_NEUTRAL,
        int $arguments = self::ARGUMENT_NEUTRAL
    ): BaseHooks {
        $this->listen($hook, $callback, $priority, $arguments);

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function applyFilter(...$args)
    {
        $hook = $this->createHook(func_get_args());
        return $this->trigger($hook->name, $hook->args);
    }

    /**
     * {@inheritDoc}
     */
    public function removeFilter(
        string $hook,
        $callback,
        int $priority = self::PRIORITY_NEUTRAL
    ): void {
        $this->remove($hook, $callback, $priority);
    }

    /**
     * Removes all filters.
     *
     * @param string $hook Hook name.
     */
    public function removeAllFilters(?string $hook = null): void
    {
        $this->removeAll($hook);
    }

    /**
     * Filters a value.
     *
     * When a filter is triggerd, all hooks are run in the order supplied when adding them.
     *
     * @param  string $filter Name of filter.
     * @param  mixed  $args   Arguments passed to the filter.
     * @return string         Always returns the value.
     */
    protected function trigger(string $filter, $args)
    {
        $this->value = $args[0] ?? '';

        if ($this->getHooks()) {
            $hooks = $this->getHooks();

            foreach ($hooks as $hook) {
                if ($hook['hook'] === $filter) {
                    $parameters = [];
                    $args[0] = $this->value;

                    for ($i = 0; $i < $hook['arguments']; $i++) {
                        if (isset($args[$i])) {
                            $parameters[] = $args[$i];
                        }
                    }

                    $this->value = call_user_func_array($this->getFunction($hook['callback']), $parameters);
                }
            }
        }

        return $this->value;
    }
}
