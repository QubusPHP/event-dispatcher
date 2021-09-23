<?php

/**
 * Qubus\EventDispatcher
 *
 * @link       https://github.com/QubusPHP/event-dispatcher
 * @copyright  2020 Joshua Parker <josh@joshuaparker.blog>
 * @copyright  2018 Filip Štamcar (original author Tor Morten Jensen)
 * @license    https://opensource.org/licenses/mit-license.php MIT License
 *
 * @since      1.0.0
 */

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\ActionFilter\Observer;
use Qubus\Tests\EventDispatcher\Hook\HookableExample;

class FilterTest extends TestCase
{
    public function setUp(): void
    {
        new HookableExample;
    }
    
    public function testIsInstanceOfHook()
    {
        Assert::assertInstanceOf('Qubus\EventDispatcher\ActionFilter\Observer', new Observer);
    }

    public function testCanHookCallable()
    {
        (new Observer)->filter->addFilter(
            'my.awesome.filter',
            function ($value) {
                return $value.' Filtered';
            }
        );
        Assert::assertEquals((new Observer)->filter->applyFilter('my.awesome.filter', 'Value Was'), 'Value Was Filtered');
    }

    public function testCanHookArray()
    {
        $class = new HookableExample;

        (new Observer)->filter->addFilter('my.amazing.filter', [$class, 'css']);

        Assert::assertEquals((new Observer)->filter->applyFilter('my.amazing.filter', 'Value Was'), 'css-hook');
    }

    public function testsListnersAreSortedByPriority()
    {
        (new Observer)->filter->addFilter(
            'my_awesome_filter',
            function ($value) {
                return $value.' Filtered';
            },
            20
        );

        (new Observer)->filter->addFilter(
            'my_awesome_filter',
            function ($value) {
                return $value.' Filtered';
            },
            8
        );

        (new Observer)->filter->addFilter(
            'my_awesome_filter',
            function ($value) {
                return $value.' Filtered';
            },
            12
        );

        (new Observer)->filter->addFilter(
            'my_awesome_filter',
            function ($value) {
                return $value.' Filtered';
            },
            40
        );

        Assert::assertEquals((new Observer)->filter->getHooks()[0]['priority'], 8);
        Assert::assertEquals((new Observer)->filter->getHooks()[3]['priority'], 12);
        Assert::assertEquals((new Observer)->filter->getHooks()[4]['priority'], 20);
        Assert::assertEquals((new Observer)->filter->getHooks()[5]['priority'], 40);
    }

    public function testSingleFilterIsRemoved()
    {
        // check the collection has 1 item
        (new Observer)->filter->addFilter('my_awesome_filter', 'my_awesome_function', 30, 1);

        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter') {
                $count++;
            }
        }
        Assert::assertEquals($count, 5);

        // check removeFilter removes the filter
        (new Observer)->filter->removeFilter('my_awesome_filter', 'my_awesome_function', 30);

        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter') {
                $count++;
            }
        }
        Assert::assertEquals($count, 4);
    }

    /**
     * @test
     */
    public function testAllFiltersAreRemoved()
    {
        // check the collection has 3 items before checking they're removed
        (new Observer)->filter->addFilter('my_awesome_filter', 'my_awesome_function', 30, 1);
        (new Observer)->filter->addFilter('my_awesome_filter', 'my_other_awesome_function', 30, 1);
        (new Observer)->filter->addFilter('my_awesome_filter_2', 'my_awesome_function_2', 30, 1);
        Assert::assertEquals(count((new Observer)->filter->getHooks()), 9);

        // check removeFilter removes the filter
        (new Observer)->filter->removeAllFilters();
        Assert::assertEquals(count((new Observer)->filter->getHooks()), 0);
    }

    /**
     * @test
     */
    public function testAllFiltersAreRemovedByHook()
    {
        // check the collection has 1 item
        (new Observer)->filter->addFilter('my_awesome_filter', 'my_awesome_function', 30, 1);
        (new Observer)->filter->addFilter('my_awesome_filter', 'my_other_awesome_function', 30, 1);
        (new Observer)->filter->addFilter('my_awesome_filter_2', 'my_awesome_function', 30, 1);
        Assert::assertEquals(count((new Observer)->filter->getHooks()), 3);

        // check removeFilter removes the filter
        (new Observer)->filter->removeAllFilters('my_awesome_filter');

        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter') {
                $count++;
            }
        }
        Assert::assertEquals($count, 0);

        // check that the other filter wasn't removed
        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter_2') {
                $count++;
            }
        }
        Assert::assertEquals($count, 1);
    }
}
