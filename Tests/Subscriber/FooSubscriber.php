<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher\Subscriber;

use Qubus\EventDispatcher\EventSubscriber;
use Qubus\EventDispatcher\GenericEvent;

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
