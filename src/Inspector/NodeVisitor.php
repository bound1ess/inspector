<?php namespace Inspector;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{

    /**
     * @var boolean
     */
    protected $insideMethod = false;

    public function __construct($marker)
    {
        $this->marker = $marker;
    }

    public function enterNode(\PhpParser\Node $node)
    {
        if ($node instanceof \PhpParser\Node\Stmt\ClassMethod) {
            $this->insideMethod = true;
        }
    }

    public function leaveNode(\PhpParser\Node $node)
    {
        if ($this->insideMethod) {
            return [$this->marker, $node];
        }
    }
}
