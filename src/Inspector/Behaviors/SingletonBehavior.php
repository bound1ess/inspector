<?php namespace Inspector\Behaviors;

trait SingletonBehavior
{

    /**
     * @var mixed
     */
    private static $instance;

    /**
     * @return mixed
     */
    public static function getInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = (new \ReflectionClass(__CLASS__))->newInstance();
        }

        return self::$instance;
    }
}
