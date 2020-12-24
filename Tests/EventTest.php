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

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\GenericEvent;
use Qubus\EventDispatcher\Event;

class EventTest extends TestCase
{
    public function testName()
    {
        $event = new GenericEvent();
        $this->assertEquals('kernel.event', $event::EVENT_NAME);
    }

    public function testArguments()
    {
        $event = new GenericEvent();
        $this->assertCount(0, $event->getArguments());
        $event->setArgument('foo', 'bar');
        $this->assertCount(1, $event->getArguments());
        $this->assertEquals('bar', $event->getArgument('foo'));
        $event->setArguments([
            'foo' => 'bar',
            'bar' => 'baz',
        ]);
        $this->assertEquals([
            'foo' => 'bar',
            'bar' => 'baz',
        ], $event->getArguments());
    }

    public function testStopPropagation()
    {
        $event = new GenericEvent();
        $this->assertFalse($event->isPropagationStopped());
        $event->stopPropagation();
        $this->assertTrue($event->isPropagationStopped());
    }

    public function testSubject()
    {
        $event = new GenericEvent(Event::EVENT_NAME, $this);
        $this->assertTrue($event->getSubject() === $this);
        $event->setSubject(null);
        $this->assertNull($event->getSubject());
    }
}
