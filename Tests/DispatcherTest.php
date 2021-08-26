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

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\Dispatcher;
use Qubus\Tests\EventDispatcher\Listener\FooListener;
use Qubus\Exception\Data\TypeException;
use Qubus\EventDispatcher\CallableListener;
use Qubus\Tests\EventDispatcher\Subscriber\FooSubscriber;
use Qubus\EventDispatcher\GenericEvent;
use Qubus\EventDispatcher\Event;

class DispatcherTest extends TestCase
{
    public function testInitialize()
    {
        $dispatcher = new Dispatcher();
        Assert::assertEmpty($dispatcher->getListeners());
    }

    public function testAddListener()
    {
        $dispatcher = new Dispatcher();
        Assert::assertEmpty($dispatcher->getListeners('foo'));
        $dispatcher->addListener('foo', new FooListener());
        Assert::assertCount(1, $dispatcher->getListeners('foo'));
        $this->expectException(TypeException::class);
        $dispatcher->addListener('foo', 'invalid-listener');
    }

    public function testHasListener()
    {
        $dispatcher = new Dispatcher();
        $listener = new FooListener();
        Assert::assertFalse($dispatcher->hasListener('foo', $listener));
        $dispatcher->addListener('foo', $listener);
        Assert::assertTrue($dispatcher->hasListener('foo', $listener));

        $callback = function () {
        };
        $dispatcher->addListener('bar', $callback);
        Assert::assertTrue($dispatcher->hasListener('bar', $callback));
    }

    public function testGetListeners()
    {
        $dispatcher = new Dispatcher();
        $listener = new FooListener();
        $dispatcher->addListener('foo', $listener);
        $callback = function () {
        };
        $dispatcher->addListener('bar', $callback);
        Assert::assertEquals([
            $listener,
            CallableListener::findByCallable($callback),
        ], $dispatcher->getListeners());
    }

    public function testAddSubscriber()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new FooSubscriber());
        Assert::assertCount(1, $dispatcher->getListeners('kernel.event'));
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
    }

    public function testRemoveListener()
    {
        $dispatcher = new Dispatcher();
        $listener = new FooListener();
        $dispatcher->addListener('bar', $listener);
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeListener('bar', $listener);
        Assert::assertCount(0, $dispatcher->getListeners('bar'));
        $dispatcher->addListener('bar', $listener);

        $dispatcher->removeListener('bar', function () {
        });
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeListener('foo', function () {
        });
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
    }

    public function testRemoveCallableListener()
    {
        $dispatcher = new Dispatcher();
        $callback = function () {
        };
        $dispatcher->addListener('bar', $callback);
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeListener('bar', $callback);
        Assert::assertCount(0, $dispatcher->getListeners('bar'));
    }

    public function testRemoveSubscriber()
    {
        CallableListener::clearListeners();
        $dispatcher = new Dispatcher();
        $subscriber = new FooSubscriber();
        $dispatcher->addSubscriber($subscriber);
        Assert::assertCount(1, $dispatcher->getListeners('kernel.event'));
        Assert::assertCount(1, $dispatcher->getListeners('bar'));
        $dispatcher->removeSubscriber($subscriber);
        Assert::assertCount(0, $dispatcher->getListeners('kernel.event'));
        Assert::assertCount(0, $dispatcher->getListeners('bar'));
    }

    public function testRemoveEventAllListeners()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new FooSubscriber());
        $dispatcher->removeAllListeners('kernel.event');
        Assert::assertCount(0, $dispatcher->getListeners('kernel.event'));
        Assert::assertNotEmpty($dispatcher->getListeners('bar'));
    }

    public function testRemoveAllListeners()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addSubscriber(new FooSubscriber());
        $dispatcher->removeAllListeners();
        Assert::assertCount(0, $dispatcher->getListeners('kernel.event'));
        Assert::assertCount(0, $dispatcher->getListeners('kernel.event'));
    }

    public function testSimpleDispatch()
    {
        $dispatcher = new Dispatcher();
        $counter = 0;
        $dispatcher->addListener('kernel.event', function () use (&$counter) {
            ++$counter;
        });
        $dispatcher->addListener('kernel.event', function () use (&$counter) {
            ++$counter;
        });
        $dispatcher->dispatch('kernel.event');
        Assert::assertEquals(2, $counter);
    }

    public function testDispatchEvent()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            Assert::assertInstanceOf(GenericEvent::class, $event);
            Assert::assertTrue($event->getSubject() === $this);
            Assert::assertEquals('foo', $event->getArgument('data'));
        });
        $dispatcher->dispatch(new GenericEvent(Event::EVENT_NAME, $this, [
            'data' => 'foo',
        ]));
    }

    public function testDispatcherWithPriority()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            Assert::assertEquals(10, $event->getArgument('number'));
            $event->setArgument('number', 100);
        }, Dispatcher::PRIORITY_DEFAULT);

        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            Assert::assertEquals(100, $event->getArgument('number'));
        }, Dispatcher::PRIORITY_LOW);

        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            Assert::assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
        }, Dispatcher::PRIORITY_HIGH);

        $dispatcher->dispatch(new GenericEvent(Event::EVENT_NAME, $this, [
            'number' => 0,
        ]));
    }

    public function testGetAllListenersSortsByPriority()
    {
        $dispatcher = new Dispatcher();

        $listener1 = new FooListener();
        $listener2 = new FooListener();
        $listener3 = new FooListener();
        $listener4 = new FooListener();
        $listener5 = new FooListener();
        $listener6 = new FooListener();

        $dispatcher->addListener('pre.event', $listener1, -10);
        $dispatcher->addListener('pre.event', $listener2);
        $dispatcher->addListener('pre.event', $listener3, 10);
        $dispatcher->addListener('post.event', $listener4, -10);
        $dispatcher->addListener('post.event', $listener5);
        $dispatcher->addListener('post.event', $listener6, 10);

        $expected = [
            'pre.event' => [$listener3, $listener2, $listener1],
            'post.event' => [$listener6, $listener5, $listener4],
        ];

        Assert::assertSame($expected['pre.event'], $dispatcher->getListeners('pre.event'));
        Assert::assertSame($expected['post.event'], $dispatcher->getListeners('post.event'));
    }

    public function testDispatchStopPropagation()
    {
        $dispatcher = new Dispatcher();
        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            Assert::assertEquals(0, $event->getArgument('number'));
            $event->setArgument('number', 10);
            $event->stopPropagation();
        });
        $dispatcher->addListener('kernel.event', function (GenericEvent $event) {
            $event->setArgument('number', 100);
        });
        $event = new GenericEvent(Event::EVENT_NAME, $this, [
            'number' => 0,
        ]);
        $dispatcher->dispatch('kernel.event', $event);
        Assert::assertEquals(10, $event->getArgument('number'));
    }
}
