<?php namespace Inspector;

class NodeVisitorTest extends \TestCase
{

    /**
     * @test
     */
    public function it_returns_the_nodes_in_the_right_order()
    {
        $visitor = new NodeVisitor("foo");

        $result = $visitor->leaveNode(\Mockery::namedMock("Continue_", "PhpParser\Node"));
        this($result)->should_have_length_of(2)->go();
        this($result)->should_be_an("array")->go();

        $result = $visitor->leaveNode(\Mockery::namedMock("Return_", "PhpParser\Node"));
        this($result)->should_have_length_of(2)->go();
        this($result)->should_be_an("array")->go();

        $result = $visitor->leaveNode(\Mockery::namedMock("If_", "PhpParser\Node"));
        this($result)->should_be_of_type("array")->go();
        this($result)->should_be_of_length(3)->go();

        this($visitor->leaveNode(\Mockery::mock("PhpParser\Node")))
            ->should_be_an("object")->go();
    }
}
