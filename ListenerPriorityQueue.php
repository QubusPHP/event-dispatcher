<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <joshua@joshuaparker.dev>
 * @copyright  2018 Filip Štamcar (original author Tor Morten Jensen)
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 */

declare(strict_types=1);

namespace Qubus\EventDispatcher;

use IteratorAggregate;
use SplObjectStorage;
use SplPriorityQueue;
use Traversable;

class ListenerPriorityQueue implements IteratorAggregate
{
    public function __construct(
        protected SplObjectStorage $storage = new SplObjectStorage(),
        protected SplPriorityQueue $queue = new SplPriorityQueue(),
    ) {
    }

    /**
     * Insert a listener to the queue.
     *
     * @param EventListener $listener
     * @param int $priority
     */
    public function insert(EventListener $listener, int $priority): void
    {
        $this->storage->attach($listener, $priority);
        $this->queue->insert($listener, $priority);
    }

    /**
     * Removes an listener from the queue.
     */
    public function detach(EventListener $listener): void
    {
        if ($this->storage->contains($listener)) {
            $this->storage->detach($listener);
            $this->refreshQueue();
        }
    }

    /**
     * Clears the queue.
     */
    public function clear(): void
    {
        $this->storage = new SplObjectStorage();
        $this->queue = new SplPriorityQueue();
    }

    /**
     * Checks whether the queue contains the listener.
     *
     * @param EventListener $listener
     * @return bool
     */
    public function contains(EventListener $listener): bool
    {
        return $this->storage->contains($listener);
    }

    /**
     * Gets all listeners.
     *
     * @return EventListener[]
     */
    public function all(): array
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
     * @return Traversable
     */
    public function getIterator(): Traversable
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
    protected function refreshQueue(): void
    {
        $this->storage->rewind();
        $this->queue = new SplPriorityQueue();
        foreach ($this->storage as $listener) {
            $priority = $this->storage->getInfo();
            $this->queue->insert($listener, $priority);
        }
    }
}
