<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
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
 * @property Hooks $hooks
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
        $this->hooks = $this->shared(function($c) { return new Hooks; });
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
        if (is_callable(@$this->_services[$id])) {
            return $this->_services[$id]($this);
        }
        return $this->_services[$id];
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
