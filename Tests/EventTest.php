<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\GenericEvent;
use Qubus\EventDispatcher\Event;

class EventTest extends TestCase
{
    public function testName()
    {
        $event = new GenericEvent();
        Assert::assertEquals('kernel.event', $event::EVENT_NAME);
    }

    public function testArguments()
    {
        $event = new GenericEvent();
        Assert::assertCount(0, $event->getArguments());
        $event->setArgument('foo', 'bar');
        Assert::assertCount(1, $event->getArguments());
        Assert::assertEquals('bar', $event->getArgument('foo'));
        $event->setArguments([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);
        Assert::assertEquals([
            'foo' => 'bar',
            'bar' => 'baz',
        ], $event->getArguments());
    }

    public function testStopPropagation()
    {
        $event = new GenericEvent();
        Assert::assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        Assert::assertTrue($event->isPropagationStopped());
    }

    public function testSubject()
    {
        $event = new GenericEvent(Event::EVENT_NAME, $this);
        Assert::assertTrue($event->getSubject() === $this);
        $event->setSubject(null);
        Assert::assertNull($event->getSubject());
    }
}
