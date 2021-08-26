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

namespace Qubus\Tests\EventDispatcher\Listener;

use Qubus\EventDispatcher\Event;
use Qubus\EventDispatcher\EventListener;

class FooListener implements EventListener
{
    public function handle(Event $event)
    {
        return true;
    }
}
