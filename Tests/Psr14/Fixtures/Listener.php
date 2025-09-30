<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Psr14\Fixtures;

class Listener
{
    public function __invoke(object $e): object
    {
        return $e;
    }
}
