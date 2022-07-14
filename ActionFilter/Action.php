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

final class Action extends BaseHooks implements Actionable, RemoveAllActions
{
    use StaticProxyAware;

    /**
     * {@inheritDoc}
     */
    public function addAction(
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
    public function doAction(...$args): void
    {
        $hook = $this->createHook(func_get_args());
        $this->trigger($hook->name, $hook->args);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAction(
        string $hook,
        $callback,
        int $priority = self::PRIORITY_NEUTRAL
    ): void {
        $this->remove($hook, $callback, $priority);
    }

    /**
     * {@inheritDoc}
     */
    public function removeAllActions(?string $hook = null): void
    {
        $this->removeAll($hook);
    }

    /**
     * Runs an action.
     *
     * When an action is triggerd, all hooks are run in the order supplied when adding them.
     *
     * @param  string $action Name of action.
     * @param  array  $args   Arguments passed to the filter.
     */
    protected function trigger(string $action, $args)
    {
        if ($this->getHooks()) {
            $hooks = $this->getHooks();

            foreach ($hooks as $hook) {
                if ($hook['hook'] === $action) {
                    $parameters = [];

                    for ($i = 0; $i < $hook['arguments']; $i++) {
                        if (isset($args[$i])) {
                            $parameters[] = $args[$i];
                        }
                    }

                    call_user_func_array($this->getFunction($hook['callback']), $parameters);
                }
            }
        }
    }
}
