<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Providers;

use Psr\EventDispatcher\ListenerProviderInterface;
use Qubus\EventDispatcher\CallbackEvent;

class CallbackProvider implements ListenerProviderInterface
{
    /** @var array<string, array<string>> */
    protected array $callbacks = [];

    /**
     * @return iterable<callable>
     */
    public function getListenersForEvent(object $event): iterable
    {
        if (!$event instanceof CallbackEvent) {
            return [];
        }

        $subject = $event->getSubject();

        foreach ($this->callbacks as $type => $callbacks) {
            if ($event instanceof $type) {
                foreach ($callbacks as $callback) {
                    if (method_exists($subject, $callback)) {
                        yield $subject->$callback(...);
                    }
                }
            }
        }
    }

    public function addCallbackMethod(string $type, string $method): self
    {
        $this->callbacks[$type][] = $method;
        return $this;
    }
}
