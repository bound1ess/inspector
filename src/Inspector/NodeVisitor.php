<?php namespace Inspector;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{

    /**
     * @var array
     */
    protected $allowed = [
        // -1 = prepend
        // 1 = append
        // 0 = both
        "If_"       => 0,
        "Return_"   => -1,
        "Foreach_"  => 0,
        "TryCatch"  => 0,
        "Case_"     => 1,
      //"Catch_"    => 1,
        "Continue_" => -1,
        "Do_"       => -1,
        "Echo_"     => 0,
      //"Else_"     => 1,
        "For_"      => 0,
        "Switch_"   => -1,
        "Throw_"    => 0,
        "While_"    => 0,
    ];

    /**
     * @var array
     */
    protected $visited = [];

    /**
     * @param object $marker
     * @return object
     */
    public function __construct($marker)
    {
        $this->marker = $marker;
    }

    /**
     * @param PhpParser\Node $node
     * @return object|array
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if (in_array(spl_object_hash($node), $this->visited)) {
            return $node;
        }

        $this->visited[] = spl_object_hash($node);

        if ($node instanceof \PhpParser\Node\Expr\FuncCall) {
            if ($node->name->parts[0] == "__inspectorMarker__") {
                // Don't want to refactor into single if statement.
                //Marker::getInstance()->expect();
                Marker::getInstance()->expect($node->getAttribute("startLine"));

                return $node;
            }
        }

        $className = explode("\\", get_class($node));
        $className = end($className);

        foreach ($this->allowed as $class => $mode) {
            if ($class === $className) {
                if (-1 === $mode) {
                    return [$this->marker, $node];
                }

                if (1 === $mode) {
                    return [$node, $this->marker];
                }

                return [$this->marker, $node, $this->marker];
            }
        }

        return $node;
    }
}
