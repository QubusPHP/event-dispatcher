<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\ActionFilter\Traits;

use Qubus\EventDispatcher\ActionFilter\Action;
use Qubus\Exception\Exception;
use ReflectionException;

trait ActionAware
{
    /**
     * Determines whether pipeline uses actions.
     */
    protected bool $useAction = false;

    /**
     * Enable actions in pipeline.
     */
    public function withAction(): static
    {
        $this->useAction = true;

        return $this;
    }
    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function doAction(...$params): void
    {
        if (! $this->useAction) {
            return;
        }

        Action::getInstance()->doAction(...$params);
    }
}
