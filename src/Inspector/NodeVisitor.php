<?php namespace Inspector;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{

    public function __construct($marker)
    {
        $this->marker = $marker;

        $this->allowed = [
            // @todo
        ];
    }

    public function leaveNode(\PhpParser\Node $node)
    {
        $className = explode("\\", get_class($node));
        $className = end($className);

        if (in_array($className, $this->allowed)) {
            echo (new \PhpParser\NodeDumper)->dump($node), PHP_EOL;
            return [$this->marker, $node];
        }

        return $node;
    }
}
