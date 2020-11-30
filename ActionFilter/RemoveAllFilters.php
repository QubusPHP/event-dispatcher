<?php

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
