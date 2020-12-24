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

use Qubus\EventDispatcher\EventSubscriber;
use Qubus\EventDispatcher\GenericEvent;

class FooSubscriber implements EventSubscriber
{
    public static function getSubscribedEvents(): array
    {
        return [
            GenericEvent::EVENT_NAME => 'onFoo',
            'bar' => 'onBar',
        ];
    }

    public function onFoo()
    {
        return true;
    }

    public function onBar()
    {
        return true;
    }
}
