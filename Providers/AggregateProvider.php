<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Providers;

use Psr\EventDispatcher\ListenerProviderInterface;

/**
 * AggregateProvider is a listener provider that allows combining multiple listener providers.
 */
class AggregateProvider implements ListenerProviderInterface
{
    /** @var ListenerProviderInterface[] */
    private array $providers = [];

    public function __construct(ListenerProviderInterface ...$providers)
    {
        $this->providers = $providers;
    }

    public function getListenersForEvent(object $event): iterable
    {
        foreach ($this->providers as $provider) {
            yield from $provider->getListenersForEvent($event);
        }
    }

    public function attach(ListenerProviderInterface $provider): void
    {
        $this->providers[] = $provider;
    }
}
