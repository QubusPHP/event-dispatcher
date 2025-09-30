<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\Tests\Psr14\Fixtures;

interface ListenerContract
{
    public function __invoke(object $e): object;
}
