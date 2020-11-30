<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\GenericEvent;
use Qubus\EventDispatcher\Event;

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
        $this->assertEquals($this->event, new GenericEvent(Event::EVENT_NAME, $this->subject, ['name' => 'Event']));
    }

    /**
     * Tests Event->getArguments().
     */
    public function testGetArguments()
    {
        // test getting all
        $this->assertSame(['name' => 'Event'], $this->event->getArguments());
    }

    public function testSetArguments()
    {
        $result = $this->event->setArguments(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $this->event->getArguments());
        $this->assertSame($this->event, $result);
    }

    public function testSetArgument()
    {
        $result = $this->event->setArgument('foo2', 'bar2');
        $this->assertSame(['name' => 'Event', 'foo2' => 'bar2'], $this->event->getArguments());
        $this->assertEquals($this->event, $result);
    }

    public function testGetArgument()
    {
        // test getting key
        $this->assertEquals('Event', $this->event->getArgument('name'));
    }

    public function testGetArgException()
    {
        $this->expectException('\Qubus\Exception\Data\TypeException');
        $this->event->getArgument('nameNotExist');
    }
}
