<?php

declare(strict_types=1);

namespace Qubus\EventDispatcher\ActionFilter\Traits;

use Qubus\EventDispatcher\ActionFilter\Filter;
use Qubus\Exception\Exception;
use ReflectionException;

trait FilterAware
{
    /**
     * Determines whether pipeline uses filters.
     */
    protected bool $useFilter = false;

    /**
     * Enable filters in pipeline.
     */
    public function withFilter(): static
    {
        $this->useFilter = true;

        return $this;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    protected function applyFilter(...$params): void
    {
        if (! $this->useFilter) {
            return;
        }

        Filter::getInstance()->applyFilter(...$params);
    }
}
