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

namespace Qubus\EventDispatcher;

use IteratorAggregate;
use SplObjectStorage;
use SplPriorityQueue;

class ListenerPriorityQueue implements IteratorAggregate
{
    /** @var SplObjectStorage $storage */
    protected $storage;

    /** @var SplPriorityQueue $queue */
    protected $queue;

    public function __construct()
    {
        $this->storage = new SplObjectStorage();
        $this->queue = new SplPriorityQueue();
    }

    /**
     * Insert a listener to the queue.
     *
     * @param int           $priority
     */
    public function insert(EventListener $listener, $priority)
    {
        $this->storage->attach($listener, $priority);
        $this->queue->insert($listener, $priority);
    }

    /**
     * Removes an listener from the queue.
     */
    public function detach(EventListener $listener)
    {
        if ($this->storage->contains($listener)) {
            $this->storage->detach($listener);
            $this->refreshQueue();
        }
    }

    /**
     * Clears the queue.
     */
    public function clear()
    {
        $this->storage = new SplObjectStorage();
        $this->queue = new SplPriorityQueue();
    }

    /**
     * Checks whether the queue contains the listener.
     *
     * @return bool
     */
    public function contains(EventListener $listener)
    {
        return $this->storage->contains($listener);
    }

    /**
     * Gets all listeners.
     *
     * @return EventListener[]
     */
    public function all()
    {
        $listeners = [];
        foreach ($this->getIterator() as $listener) {
            $listeners[] = $listener;
        }

        return $listeners;
    }

    /**
     * Clones and returns a iterator.
     *
     * @return SplPriorityQueue
     */
    public function getIterator()
    {
        $queue = clone $this->queue;
        if (! $queue->isEmpty()) {
            $queue->top();
        }

        return $queue;
    }

    /**
     * Refreshes the status of the queue.
     */
    protected function refreshQueue()
    {
        $this->storage->rewind();
        $this->queue = new SplPriorityQueue();
        foreach ($this->storage as $listener) {
            $priority = $this->storage->getInfo();
            $this->queue->insert($listener, $priority);
        }
    }
}
