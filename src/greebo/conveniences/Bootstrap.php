<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

use greebo\essence\Greebo;

abstract class Bootstrap
{
    protected $_container;
      
    function __construct($env)
    {
        $this->_container = $this->container();
        $this->_container->env = $env;
        
        $this->init();
        $this->$env();
    }
    
    abstract function init();
    
    function container()
    {
        return new Container;
    }
    
    function run()
    {
        try {
            $greebo = $this->greebo();
            $greebo->unleash();
        } catch (Exception $e) {
            if ($this->_container->debug) {
                // TODO show backtrace
            } else {
                // TODO render error500 page
            }
        }
    }

    function greebo()
    {
      return new \greebo\essence\Greebo($this->_container);
    }
}
