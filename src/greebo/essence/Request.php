<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

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
        if ('POST' === $this->header('request_method')) {
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
