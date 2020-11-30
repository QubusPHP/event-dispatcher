<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use Psr\EventDispatcher\StoppableEventInterface;

abstract class BaseEvent implements StoppableEventInterface
{
    protected bool $propagationStopped = false;

    /**
     * {@inheritdoc}
     */
    public function isPropagationStopped(): bool
    {
        return $this->propagationStopped;
    }

    /**
     * Stops the propagation of the event to further event listeners.
     */
    public function stopPropagation(): void
    {
        $this->propagationStopped = true;
    }
}
