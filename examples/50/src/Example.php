<?php

class Example
{

    public function doSomething($foo)
    {
        if (BAR === $foo) {
            return false;
        }

        return [1, 2, 3];
    }
}
