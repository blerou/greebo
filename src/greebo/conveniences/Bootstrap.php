<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

abstract class Bootstrap
{
    private $_container;
      
    function __construct($env)
    {
        $container = $this->container();
        $container->env = $env;
        
        $this->init();
        $this->$env();
    }
    
    abstract function init();
    
    function container()
    {
        if (null === $this->_container) {
            $this->_container = new Container;
        }
        return $this->_container;
    }
    
    function run()
    {
        try {
            $greebo = $this->greebo();
            $greebo->unleash();
        } catch (Exception $e) {
            if ($this->container()->debug) {
                // TODO show backtrace
            } else {
                // TODO render error500 page
            }
        }
    }

    function greebo()
    {
      return new \greebo\essence\Greebo($this->container());
    }
}
