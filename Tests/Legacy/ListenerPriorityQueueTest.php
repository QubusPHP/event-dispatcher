<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Legacy;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\Legacy\Dispatcher;
use Qubus\EventDispatcher\Legacy\ListenerPriorityQueue;
use Qubus\EventDispatcher\Tests\Legacy\Listener\FooListener;

class ListenerPriorityQueueTest extends TestCase
{
    public function testInsert()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        Assert::assertFalse($queue->contains($listener));
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        Assert::assertTrue($queue->contains($listener));
    }

    public function testContains()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        Assert::assertFalse($queue->contains($listener));
    }

    public function testDetach()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        Assert::assertTrue($queue->contains($listener));
        $queue->insert(new FooListener(), Dispatcher::PRIORITY_DEFAULT);
        $queue->detach($listener);
        Assert::assertFalse($queue->contains($listener));
    }

    public function testClear()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        $queue->clear();
        Assert::assertFalse($queue->contains($listener));
    }

    public function testAll()
    {
        $queue = new ListenerPriorityQueue();
        $listener = new FooListener();
        $queue->insert($listener, Dispatcher::PRIORITY_DEFAULT);
        Assert::assertEquals([$listener], $queue->all());
    }
}
