<?php namespace Inspector\Behaviors;

class SingletonBehaviorTest extends \TestCase
{

    /**
     * @test
     */
    public function it_ensures_that_you_always_get_the_same_instance()
    {
        $stub = new \Inspector\Stubs\SingletonStub;

        this($stub->getInstance())->should_be_equal_to($stub->getInstance())->go();
    }
}
