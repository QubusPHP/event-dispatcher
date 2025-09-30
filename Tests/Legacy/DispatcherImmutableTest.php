<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Legacy;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\Legacy\DispatcherImmutable;
use Qubus\EventDispatcher\Legacy\GenericEvent;
use Qubus\EventDispatcher\Tests\Legacy\Listener\FooListener;

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
        $this->innerDispatcher = $this->getMockBuilder('Qubus\EventDispatcher\Legacy\EventDispatcher')->getMock();
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

        Assert::assertSame($resultEvent, $this->dispatcher->dispatch('kernel.event', $event));
    }

    public function testGetListeners()
    {
        $this->innerDispatcher->expects($this->once())
            ->method('getListeners')
            ->with('foo')
            ->willReturn(['result']);

        Assert::assertSame(['result'], $this->dispatcher->getListeners('foo'));
    }

    public function testHasListener()
    {
        $listener = new FooListener();

        $this->innerDispatcher->expects($this->once())
            ->method('hasListener')
            ->with('foo')
            ->willReturn(true);

        Assert::assertTrue($this->dispatcher->hasListener('foo', $listener));
    }

    public function testAddListenerThrowsAnException()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->dispatcher->addListener('event', function () {
            return 'foo';
        });
    }

    public function testAddSubscriberThrowsAnException()
    {
        $this->expectException(\BadMethodCallException::class);

        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\Legacy\EventSubscriber')->getMock();

        $this->dispatcher->addSubscriber($subscriber);
    }

    public function testRemoveListenerThrowsAnException()
    {
        $this->expectException(\BadMethodCallException::class);

        $this->dispatcher->removeListener('event', function () {
            return 'foo';
        });
    }

    public function testRemoveSubscriberThrowsAnException()
    {
        $this->expectException(\BadMethodCallException::class);

        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\Legacy\EventSubscriber')->getMock();

        $this->dispatcher->removeSubscriber($subscriber);
    }
}
