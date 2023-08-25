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

interface RemoveAllActions
{
    /**
     * Removes all actions.
     *
     * @param string|null $hook Hook name.
     */
    public function removeAllActions(?string $hook = null): void;
}
