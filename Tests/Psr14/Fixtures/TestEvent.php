<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Psr14\Fixtures;

use Qubus\EventDispatcher\BaseEvent;

class TestEvent extends BaseEvent
{
    public static function occur(bool $stopped = false): self
    {
        $self = new self();
        $self->propagationStopped = $stopped;

        return $self;
    }
}
