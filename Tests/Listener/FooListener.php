<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher\Listener;

use Qubus\EventDispatcher\Event;
use Qubus\EventDispatcher\EventListener;

class FooListener implements EventListener
{
    public function handle(Event $event)
    {
        return true;
    }
}
