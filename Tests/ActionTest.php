<?php

declare(strict_types=1);

namespace Qubus\Tests\EventDispatcher;

use PHPUnit\Framework\TestCase;
use Qubus\EventDispatcher\ActionFilter\Observer;
use Qubus\Tests\EventDispatcher\HookableExample;

class ActionTest extends TestCase
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
        (new Observer)->action->addAction(
            'hello.world.2',
            function () {
                echo 'Hello World #2!';
            }
        );
        $this->expectOutputString('Hello World #2!');
        (new Observer)->action->doAction('hello.world.2');
    }

    /**
     * @expectedException \Qubus\Exception\Exception
     * @expectedException $callback is not a Callable.
     */
    public function testCanNotHookBoolean()
    {
        (new Observer)->action->addAction('boolean.hook', true);
        (new Observer)->action->doAction('boolean.hook');
    }

    public function testHookWithParameters()
    {
        (new Observer)->action->addAction(
            'hello.world.5',
            function () {
                echo 'Hello, ' . func_get_args()[0] . ' #5!';
            },
            20
        );

        $this->expectOutputString('Hello, World #5!');
        (new Observer)->action->doAction('hello.world.5', 'World');
    }

    public function testsHooksAreSortedByPriority()
    {
        (new Observer)->action->addAction(
            'hello.world.4',
            function () {
                echo 'Hello World, #4!';
            },
            20
        );

        (new Observer)->action->addAction(
            'hello.world.3',
            function () {
                echo 'Hello World, #3!';
            },
            12
        );

        (new Observer)->action->addAction(
            'hello.world.0',
            function () {
                echo 'Hello World, #0!';
            },
            8
        );

        (new Observer)->action->addAction(
            'hello.world.6',
            function () {
                echo 'Hello World, #6!';
            },
            40
        );

        $this->assertEquals((new Observer)->action->getHooks()[0]['priority'], 8);
        $this->assertEquals((new Observer)->action->getHooks()[3]['priority'], 12);
        $this->assertEquals((new Observer)->action->getHooks()[4]['priority'], 20);
        $this->assertEquals((new Observer)->action->getHooks()[6]['priority'], 40);
    }

    public function testSingleActionIsRemoved()
    {
        // check the collection has 1 item
        (new Observer)->action->addAction('hello.world.3', 'hello_world', 30, 1);
        (new Observer)->action->addAction('hello.world.3', 'hello_world', 10, 1);

        $count = 0;
        foreach ((new Observer)->action->getHooks() as $hook) {
            if ($hook['hook'] === 'hello.world.3') {
                $count++;
            }
        }
        $this->assertEquals($count, 3);

        // check removeAction removes the correct action
        (new Observer)->action->removeAction('hello.world.3', 'hello_world', 30);

        $count = 0;
        foreach ((new Observer)->action->getHooks() as $hook) {
            if ($hook['hook'] === 'hello.world.3') {
                $count++;
            }
        }
        $this->assertEquals($count, 2);

        // check that the action with priority 10 still exists in the collection
        // (only the action with priority 30 should've been removed)
        $priority = 0;
        foreach ((new Observer)->action->getHooks() as $hook) {
            if ($hook['hook'] === 'hello.world.3') {
                $priority = $hook['priority'];
            }
        }
        $this->assertEquals($priority, 12);
    }

    /**
     * @test
     */
    public function testAllActionsAreRemoved()
    {
        // check the collection has 3 items before checking they're removed
        (new Observer)->action->addAction('hello.world.3', 'hello_world', 30, 1);
        (new Observer)->action->addAction('hello.world.3', 'my_other_great_function', 30, 1);
        (new Observer)->action->addAction('hello.world.3_2', 'hello_world', 30, 1);
        $this->assertEquals(count((new Observer)->action->getHooks()), 11);

        // check removeFilter removes the filter
        (new Observer)->action->removeAllActions();
        $this->assertEquals(count((new Observer)->action->getHooks()), 0);
    }

    /**
     * @test
     */
    public function testAllActionsAreRemovedByHook()
    {
        // check the collection has 3 items before checking they're removed correctly
        (new Observer)->action->addAction('hello.world.3', 'hello_world', 30, 1);
        (new Observer)->action->addAction('hello.world.3', 'my_other_great_function', 30, 1);
        (new Observer)->action->addAction('hello.world.3_2', 'hello_world', 30, 1);
        $this->assertEquals(count((new Observer)->action->getHooks()), 3);

        // check removeAction removes the filter
        (new Observer)->action->removeAllActions('hello.world.3');

        $count = 0;
        foreach ((new Observer)->action->getHooks() as $hook) {
            if ($hook['hook'] == 'hello.world.3') {
                $count++;
            }
        }
        $this->assertEquals($count, 0);

        // check that the other action wasn't removed
        $count = 0;
        foreach ((new Observer)->action->getHooks() as $hook) {
            if ($hook['hook'] == 'hello.world.3_2') {
                $count++;
            }
        }
        $this->assertEquals($count, 1);
    }
}
