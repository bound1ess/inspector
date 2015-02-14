<?php

class ExampleTest extends PHPUnit_Framework_TestCase
{

    /**
     * @test
     */
    public function it_works()
    {
        $example = new Example;

        $this->assertFalse($example->doSomething("foobar"));
        //$this->assertInternalType("array", $example->doSomething("baz"));
    }
}
