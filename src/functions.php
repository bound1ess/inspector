<?php

function __inspectorMarker__($file, $line)
{
    Inspector\Marker::getInstance()->add($file, $line);
}
