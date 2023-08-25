<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\GenericEvent;
use Qubus\EventDispatcher\Event;
use Qubus\Exception\Data\TypeException;

class GenericEventTest extends TestCase
{
    /**
     * @var GenericEvent
     */
    private $event;

    private $subject;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp(): void
    {
        $this->subject = new \stdClass();
        $this->event = new GenericEvent(Event::EVENT_NAME, $this->subject, ['name' => 'Event']);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown(): void
    {
        $this->subject = null;
        $this->event = null;
    }

    public function testConstruct()
    {
        Assert::assertEquals($this->event, new GenericEvent(Event::EVENT_NAME, $this->subject, ['name' => 'Event']));
    }

    /**
     * Tests Event->getArguments().
     */
    public function testGetArguments()
    {
        // test getting all
        Assert::assertSame(['name' => 'Event'], $this->event->getArguments());
    }

    public function testSetArguments()
    {
        $result = $this->event->setArguments(['foo' => 'bar']);
        Assert::assertSame(['foo' => 'bar'], $this->event->getArguments());
        Assert::assertSame($this->event, $result);
    }

    public function testSetArgument()
    {
        $result = $this->event->setArgument('foo2', 'bar2');
        Assert::assertSame(['name' => 'Event', 'foo2' => 'bar2'], $this->event->getArguments());
        Assert::assertEquals($this->event, $result);
    }

    public function testGetArgument()
    {
        // test getting key
        Assert::assertEquals('Event', $this->event->getArgument('name'));
    }

    public function testGetArgException()
    {
        $this->expectException(TypeException::class);

        $this->event->getArgument('nameNotExist');
    }
}
