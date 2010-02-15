<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) 2010 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
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
        if (method_exists($this, $env)) {
            $this->$env();
        }
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
        } catch (\Exception $e) {
            if ($this->container()->debug) {
                $this->container()->response
                    ->status(200)->content($this->backtrace())->send();
            } else {
                $this->container()->response
                    ->status(500)->content(null)->send();
            }
        }
    }

    function greebo()
    {
      return new \greebo\essence\Greebo($this->container());
    }

    function backtrace()
    {
        $result = '';
        foreach (debug_backtrace() as $row) {
            $result .= $row['file'];
            // TODO create simple response
        }
        return $result;
    }
}
