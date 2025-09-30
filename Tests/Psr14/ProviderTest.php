<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Psr14;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\EventDispatcher\ListenerProviderInterface;
use Psr\EventDispatcher\StoppableEventInterface;
use Qubus\EventDispatcher\Providers\SimpleProvider;
use Qubus\EventDispatcher\Tests\Psr14\Fixtures\Listener;
use Qubus\EventDispatcher\Tests\Psr14\Fixtures\TestEvent;

use function iterator_to_array;

class ProviderTest extends TestCase
{
    /** @var StoppableEventInterface|MockObject */
    private $event;
    /** @var Listener[]|MockObject[] */
    private $listeners;
    /** @var ListenerProviderInterface */
    private $provider;

    public function testItShouldAddListenerToCollection(): void
    {
        $this->givenAnEvent();
        $this->givenSomeListeners();
        $this->whenListenersAreAddedToProvider();
        $this->thenProviderHaveTheListenersSubscribedToTheEvent();
    }


    private function givenAnEvent(): void
    {
        $this->event = new TestEvent();
    }

    private function givenSomeListeners(): void
    {
        $this->listeners = [
            Listener::class,
            Listener::class,
        ];
    }

    private function whenListenersAreAddedToProvider(): void
    {
        $this->provider = new SimpleProvider();
        foreach ($this->listeners as $listener) {
            $this->provider->addListener(eventClass: TestEvent::class, listener: function () use ($listener) {
                return $listener;
            });
        }
    }

    private function thenProviderHaveTheListenersSubscribedToTheEvent(): void
    {
        Assert::assertCount(2, iterator_to_array($this->provider->getListenersForEvent($this->event)));
    }
}
