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

interface RemoveAllFilters
{
    /**
     * Removes all filters.
     *
     * @param string $hook Hook name.
     */
    public function removeAllFilters(?string $hook = null): void;
}
