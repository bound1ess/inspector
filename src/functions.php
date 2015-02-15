<?php

function __inspectorMarker__($file, $line)
{
    Inspector\Marker::getInstance()->useFile($file);

    Inspector\Marker::getInstance()->execute($line);
}
