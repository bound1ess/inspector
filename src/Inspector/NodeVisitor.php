<?php namespace Inspector;

class NodeVisitor extends \PhpParser\NodeVisitorAbstract
{

    /**
     * @param object $marker
     * @return object
     */
    public function __construct($marker)
    {
        $this->marker = $marker;

        $this->allowed = [
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
    }

    /**
     * @param PhpParser\Node $node
     * @return object|array
     */
    public function leaveNode(\PhpParser\Node $node)
    {
        $className = explode("\\", get_class($node));
        $className = end($className);

        foreach ($this->allowed as $class => $mode) {
            if ($class === $className) {
                $attrs = $node->getAttributes();
                list($start, $end) = [$attrs["startLine"], $attrs["endLine"]];

                if (-1 === $mode) {
                    Marker::getInstance()->expect($start - 1);

                    return [$this->marker, $node];
                }

                if (1 === $mode) {
                    Marker::getInstance()->expect($end + 1);

                    return [$node, $this->marker];
                }

                Marker::getInstance()->expect($start - 1);
                Marker::getInstance()->expect($end + 1);

                return [$this->marker, $node, $this->marker];
            }
        }

        return $node;
    }
}
