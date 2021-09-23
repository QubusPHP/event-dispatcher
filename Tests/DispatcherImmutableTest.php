<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <josh@joshuaparker.blog>
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *
 * @since      1.0.0
 */

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\DispatcherImmutable;
use Qubus\Tests\EventDispatcher\Listener\FooListener;
use Qubus\Tests\EventDispatcher\Subscriber\FooSubscriber;
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
        $listener = new FooListener;

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

        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\EventSubscriber')->getMock();

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

        $subscriber = $this->getMockBuilder('Qubus\EventDispatcher\EventSubscriber')->getMock();

        $this->dispatcher->removeSubscriber($subscriber);
    }
}
