<?php namespace Inspector\Behaviors;

trait SingletonBehavior
{

    /**
     * @var mixed
     */
    protected $_instance;

    public function getInstance()
    {
        if (is_null($this->_instance)) {
            $this->_instance = new static;
        }

        return $this->_instance;
    }
}
