<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\ActionFilter\Observer;
use Qubus\Tests\EventDispatcher\HookableExample;

class FilterTest extends TestCase
{
    public function setUp()
    {
        new HookableExample;
    }
    
    public function testIsInstanceOfHook()
    {
        $this->assertInstanceOf('Qubus\EventDispatcher\ActionFilter\Observer', new Observer);
    }

    public function testCanHookCallable()
    {
        (new Observer)->filter->addFilter(
            'my.awesome.filter',
            function ($value) {
                return $value.' Filtered';
            }
        );
        $this->assertEquals((new Observer)->filter->applyFilter('my.awesome.filter', 'Value Was'), 'Value Was Filtered');
    }

    public function testCanHookArray()
    {
        $class = new HookableExample;

        (new Observer)->filter->addFilter('my.amazing.filter', [$class, 'css']);

        $this->assertEquals((new Observer)->filter->applyFilter('my.amazing.filter', 'Value Was'), 'css-hook');
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

        $this->assertEquals((new Observer)->filter->getHooks()[0]['priority'], 8);
        $this->assertEquals((new Observer)->filter->getHooks()[3]['priority'], 12);
        $this->assertEquals((new Observer)->filter->getHooks()[4]['priority'], 20);
        $this->assertEquals((new Observer)->filter->getHooks()[5]['priority'], 40);
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
        $this->assertEquals($count, 5);

        // check removeFilter removes the filter
        (new Observer)->filter->removeFilter('my_awesome_filter', 'my_awesome_function', 30);

        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter') {
                $count++;
            }
        }
        $this->assertEquals($count, 4);
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
        $this->assertEquals(count((new Observer)->filter->getHooks()), 9);

        // check removeFilter removes the filter
        (new Observer)->filter->removeAllFilters();
        $this->assertEquals(count((new Observer)->filter->getHooks()), 0);
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
        $this->assertEquals(count((new Observer)->filter->getHooks()), 3);

        // check removeFilter removes the filter
        (new Observer)->filter->removeAllFilters('my_awesome_filter');

        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter') {
                $count++;
            }
        }
        $this->assertEquals($count, 0);

        // check that the other filter wasn't removed
        $count = 0;
        foreach ((new Observer)->filter->getHooks() as $hook) {
            if ($hook['hook'] == 'my_awesome_filter_2') {
                $count++;
            }
        }
        $this->assertEquals($count, 1);
    }
}
