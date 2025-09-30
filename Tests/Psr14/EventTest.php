<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Psr14;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\Tests\Psr14\Fixtures\TestEvent;

class EventTest extends TestCase
{
    /** @var TestEvent */
    private $eventClass;
    /** @var TestEvent */
    private $event;

    public function testItShouldGetName(): void
    {
        $this->havingAnEvent();
        $this->whenEventOccurred();
        $this->thenEventHaveACorrectName();
        $this->andThenEventCanPropagate();
    }

    public function testItShouldBeStoppedEvent(): void
    {
        $this->havingAnEvent();
        $this->whenStoppedEventOccurred();
        $this->thenEventHasStoppedPropagation();
    }

    private function havingAnEvent(): void
    {
        $this->eventClass = new TestEvent();
    }

    private function whenEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur();
    }

    private function thenEventHaveACorrectName(): void
    {
        Assert::assertFalse($this->event->isPropagationStopped());
    }

    private function whenStoppedEventOccurred(): void
    {
        $eventClass = $this->eventClass;
        $this->event = $eventClass::occur(true);
    }

    private function thenEventHasStoppedPropagation(): void
    {
        Assert::assertTrue($this->event->isPropagationStopped());
    }

    private function andThenEventCanPropagate(): void
    {
        Assert::assertFalse($this->event->isPropagationStopped());
    }
}
