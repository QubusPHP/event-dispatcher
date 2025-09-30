<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher;

interface CallbackEvent
{
    /**
     * Returns the subject of the event.
     * This is the object on which callback methods will be called, if applicable.
     *
     * @return object
     */
    public function getSubject(): object;
}
