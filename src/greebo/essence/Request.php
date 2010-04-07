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
 * Request class
 *
 * This class is a simple Value object.
 * It also introduces simple API that is according to Response API
 * It's advantage that provide a default return value as second parameter.
 *
 * Request can also store some related values, as attributes, through
 * simple properties.
 *
 * @package    greebo
 * @subpackage essence
 * @author     blerou
 */
class Request
{
    private $_get;
    private $_post;
    private $_files;
    private $_cookie;
    private $_server;
    private $_attrs = array();

    public function __construct(array $get, array $post, array $files, array $cookie, array $server)
    {
        $this->_get = $get;
        $this->_post = $post;
        $this->_files = $files;
        $this->_cookie = $cookie;
        $this->_server = $server;
    }

    public function get($name, $default = null)
    {
        return isset($this->_get[$name])
            ? $this->_get[$name]
            : $default;
    }

    public function post($name, $default = null)
    {
        return isset($this->_post[$name])
            ? $this->_post[$name]
            : $default;
    }

    public function param($name, $default = null)
    {
        $param = $this->_get;
        if ('POST' === $this->header('REQUEST_METHOD')) {
            $param += $this->_post;
        }
        return isset($param[$name])
            ? $param[$name]
            : $default;
    }

    public function files($name, $default = null)
    {
        return isset($this->_files[$name])
            ? $this->_files[$name]
            : $default;
    }

    public function cookie($name, $default = null)
    {
        return isset($this->_cookie[$name])
            ? $this->_cookie[$name]
            : $default;
    }
    
    public function server($name, $default = null)
    {
        return isset($this->_server[$name])
            ? $this->_server[$name]
            : $default;
    }

    public function header($name, $default = null)
    {
        return $this->server($name, $default);
    }

    public function __get($name)
    {
        return isset($this->_attrs[$name])
            ? $this->_attrs[$name]
            : null;
    }

    public function __set($name, $value)
    {
        $this->_attrs[$name] = $value;
    }
    
    public function __isset($name)
    {
        return isset($this->_attrs[$name]);
    }
}