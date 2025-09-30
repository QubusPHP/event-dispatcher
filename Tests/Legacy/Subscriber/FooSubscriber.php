<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Legacy\Subscriber;

use Qubus\EventDispatcher\Legacy\EventSubscriber;
use Qubus\EventDispatcher\Legacy\GenericEvent;

class FooSubscriber implements EventSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            GenericEvent::EVENT_NAME => 'onFoo',
            'bar' => 'onBar',
        ];
    }

    public function onFoo()
    {
        return true;
    }

    public function onBar()
    {
        return true;
    }
}
