<?php namespace Inspector;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{

    public function __construct($marker)
    {
        $this->marker = $marker;
    }

    public function leaveNode(\PhpParser\Node $node)
    {
        var_dump($node);
    }
}
