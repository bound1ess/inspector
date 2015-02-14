<?php

// @notable
error_reporting(E_ALL);

if ( ! defined("INSPECTOR")) {
    define("INSPECTOR", __DIR__."/..");
}

if ( ! defined("INSPECTOR_WD")) {
    define("INSPECTOR_WD", getcwd());
}

if (is_readable($autoloader = INSPECTOR."/vendor/autoload.php")) {
    require $autoloader;
} else {
    require INSPECTOR."/../../autoload.php";
}
