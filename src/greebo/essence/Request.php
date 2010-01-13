<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

/**
 * Simple request class
 * 
 * Request's responsibility is to:
 *  - wrap main PHP superglobals arrays (through method calls)
 *  - introduces simple API that is according to Response API
 * It's advantage that provide a default return value as second parameter.
 *
 * Request can also store some related values, as attributes, through
 * simple properties.
 *
 * @package    greebo
 * @subpackage essence
 * @author     blerou
 * 
 * @method string cookie(string $name, $def = null)  getter of a $_COOKIE
 * @method array  files(string $name, $def = null)   getter of an uploaded file ($_FILES)
 * @method mix    get(string $name, $def = null)     getter of a $_GET index
 * @method mix    post(string $name, $def = null)    getter of a $_POST index
 */
class Request
{
    private $_attrs = array();

    function header($name, $def = null)
    {
        $name = strtoupper($name);
        return isset($_SERVER[$name])
            ? $_SERVER[$name]
            : $def;
    }
    
    function param($name, $def = null)
    {
        $param = $_GET;
        if ('POST' === $this->header('REQUEST_METHOD')) {
            $param = array_merge($param, $_POST);
        }
        return isset($param[$name]) 
            ? $param[$name]
            : $def;
    }
    
    function __call($method, $args)
    {
        $param = @$GLOBALS['_'.strtoupper($method)];
        if (isset($param[$args[0]])) {
            return $param[$args[0]];
        }
        if (isset($args[1])) {
            return $args[1];
        }
        return null;
    }

    function __get($name)
    {
        return isset($this->_attrs[$name])
            ? $this->_attrs[$name]
            : null;
    }

    function __set($name, $val)
    {
        $this->_attrs[$name] = $val;
    }
}
