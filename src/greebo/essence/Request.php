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
