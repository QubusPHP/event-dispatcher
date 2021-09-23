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
use Qubus\EventDispatcher\CallableListener;

class CallableListenerTest extends TestCase
{
    public function testGetCallable()
    {
        $callback = function () {
            return true;
        };
        $listener = new CallableListener($callback);
        Assert::assertTrue($listener->getCallable() === $callback);
    }

    public function testCreateFromCallable()
    {
        $listener = CallableListener::createFromCallable(function () {
            return true;
        });
        Assert::assertInstanceOf(CallableListener::class, $listener);
    }

    public function testFindByCallable()
    {
        $callback = function () {
            return true;
        };
        Assert::assertTrue(CallableListener::createFromCallable($callback) === CallableListener::findByCallable($callback));
        Assert::assertFalse(CallableListener::findByCallable(function () {
        }));
    }
}
