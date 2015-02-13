<?php namespace Inspector;

class InspectorTest extends \TestCase
{

    /**
     * @test
     */
    public function one_plus_two_equals_three()
    {
        expect(this(1 + 2))->to_be_equal_to(3)->go();
    }
}
