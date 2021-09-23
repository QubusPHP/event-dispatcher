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
use Qubus\EventDispatcher\ListenerPriorityQueue;
use Qubus\Tests\EventDispatcher\Listener\FooListener;
use Qubus\EventDispatcher\Dispatcher;

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
