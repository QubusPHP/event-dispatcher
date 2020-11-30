<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\DispatcherImmutable;
use Qubus\Tests\EventDispatcher\FooListener;
use Qubus\Tests\EventDispatcher\FooSubscriber;
use Qubus\EventDispatcher\GenericEvent;

class DispatcherImmutableTest extends TestCase
{
    /**
     * @var MockObject
     */
    private $innerDispatcher;

    /**
     * @var DispatcherImmutable $dispatcher
     */
    private DispatcherImmutable $dispatcher;

    protected function setUp(): void
    {
        $this->innerDispatcher = $this->getMockBuilder('Qubus\EventDispatcher\EventDispatcher')->getMock();
        $this->dispatcher = new DispatcherImmutable($this->innerDispatcher);
    }

    public function testDispatcher()
    {
        $event = new GenericEvent();
        $resultEvent = new GenericEvent();

        $this->innerDispatcher->expects($this->once())
            ->method('dispatch')
            ->with('kernel.event')
            ->willReturn($resultEvent);

        $this->assertSame($resultEvent, $this->dispatcher->dispatch('kernel.event', $event));
    }

    public function testGetListeners()
    {
        $this->innerDispatcher->expects($this->once())
            ->method('getListeners')
            ->with('foo')
            ->willReturn(['result']);

        $this->assertSame(['result'], $this->dispatcher->getListeners('foo'));
    }

    public function testHasListener()
    {
        $listener = new FooListener;

        $this->innerDispatcher->expects($this->once())
            ->method('hasListener')
            ->with('foo')
            ->willReturn(true);

        $this->assertTrue($this->dispatcher->hasListener('foo', $listener));
    }

    public function testAddListenerThrowsAnException()
    {
        $this->expectException('\BadMethodCallException');
        $this->dispatcher->addListener('event', function () {
            return 'foo';
        });
    }

    public function testAddSubscriberThrowsAnException()
    {
        $this->expectException('\BadMethodCallException');
        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\EventSubscriber')->getMock();

        $this->dispatcher->addSubscriber($subscriber);
    }

    public function testRemoveListenerThrowsAnException()
    {
        $this->expectException('\BadMethodCallException');
        $this->dispatcher->removeListener('event', function () {
            return 'foo';
        });
    }

    public function testRemoveSubscriberThrowsAnException()
    {
        $this->expectException('\BadMethodCallException');
        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\EventSubscriber')->getMock();

        $this->dispatcher->removeSubscriber($subscriber);
    }
}
