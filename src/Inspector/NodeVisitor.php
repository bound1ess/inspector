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
     * @var boolean
     */
    protected $dryRun = false;

    /**
     * @param object $marker
     * @return object
     */
    public function __construct($marker)
    {
        $this->marker = $marker;
    }

    /**
     * @param boolean $flag
     * @return void
     */
    public function setDryRun($flag)
    {
        $this->dryRun = $flag;
    }

    /**
     * @param PhpParser\Node $node
     * @return object|array
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        if (($node instanceof \PhpParser\Node\Expr\FuncCall)
            and $node->name->parts[0] == "__inspectorMarker__") {
            Marker::getInstance()->expect($node->getAttribute("startLine"));

            return $node;
        }

        if ($this->dryRun) {
            return $node;
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
