<?php

/**
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) 2010 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace greebo\essence;

/**
 * HTTP request class
 *
 * This class is a simple value object.
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
class HttpRequest
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
        if ('POST' === $this->server('REQUEST_METHOD')) {
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