<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Legacy\Listener;

use Qubus\EventDispatcher\Legacy\Event;
use Qubus\EventDispatcher\Legacy\EventListener;

class FooListener implements EventListener
{
    public function handle(Event $event)
    {
        return true;
    }
}
