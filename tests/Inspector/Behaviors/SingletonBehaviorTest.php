<?php namespace Inspector\Behaviors;

use Inspector\Stubs\SingletonStub as Stub;

class SingletonBehaviorTest extends \TestCase
{

    /**
     * @test
     */
    public function it_ensures_that_you_always_get_the_same_instance()
    {
        this(Stub::getInstance())->should_be_equal_to(Stub::getInstance())->go();
    }
}
