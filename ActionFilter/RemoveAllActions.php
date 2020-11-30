<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\ActionFilter;

interface RemoveAllActions
{
    /**
     * Removes all actions.
     *
     * @param string $hook Hook name.
     */
    public function removeAllActions(?string $hook = null): void;
}
