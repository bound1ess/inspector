<?php

class ExampleTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_does_something()
    {
        $example = new Example;

        $this->assertEquals(123, $example->foo(123));
        $this->assertNull($example->foo(false));
    }
}
