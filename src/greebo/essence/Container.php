<?php

/*
 * This file is part of the greebo essence pack.
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

namespace greebo\essence;

/**
 * This class almost completely based on Fabien Potencier's Pimple class.
 *
 * This is a small and simple dependency injection container, where you can
 * store parameters or lazy loaded object instances as lambdas.
 *
 * @see http://github.com/fabpot/Pimple
 *
 * @property Event $events
 * @property Request $request
 * @property Response $response
 */
class Container
{
    /**
     * Services' holder
     *
     * @var array
     */
    private $_services = array();

    /**
     * Constructor
     */
    function __construct()
    {
        $this->init();
    }

    /**
     * Initialization
     *
     * Setup the minimal set of services, what needed for greebo handle a request.
     *
     * @return null
     */
    function init()
    {
        $this->request = $this->shared(function($c) { return new Request; });
        $this->response = $this->shared(function($c) { return new Response($c); });
        $this->event = $this->shared(function($c) { return new Event; });
    }

    /**
     * Set a parameter or a lambda
     *
     * @param string $id
     * @param mixed $val
     */
    function __set($id, $val)
    {
        $this->_services[$id] = $val;
    }

    /**
     * Get a parameter or an object instance
     *
     * @param  string $id
     * @return mixed
     */
    function __get($id)
    {
        if (!isset($this->_services[$id])) {
            return null;
        }
        if (is_callable($this->_services[$id])) {
            return $this->_services[$id]($this);
        }
        return $this->_services[$id];
    }

    function __isset($id)
    {
        return isset($this->_services[$id]);
    }

    /**
     * Wrap the given Closure with another which stores the result of the former.
     *
     * @param  Closure $callable
     * @return Closure
     */
    function shared($callable)
    {
        return function ($c) use ($callable) {
            static $object;
            if (!$object) {
                $object = $callable($c);
            }
            return $object;
        };
    }
}
