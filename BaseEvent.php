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
