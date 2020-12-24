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

interface EventSubscriber
{
    /**
     * Returns an array of event names this subscriber wants to listen to.
     *
     * The array key is the name of the hook. The value can be:
     *
     *  * The method name. Priority defaults to 0.
     *  * An array with the method name and priority.
     *  * An array or arrays with method name and/or priority.
     *
     * For example:
     *
     *  * ['eventName' => 'methodName']
     *  * ['eventName' => ['methodName', $priority]]
     *  * ['eventName' => [['methodName1', $priority], ['methodName2']]]
     *
     * @return array The event names to listen to.
     */
    public static function getSubscribedEvents(): array;
}
