<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher;

interface EventListener
{
    /**
     * Handles an event.
     */
    public function handle(Event $event);
}
