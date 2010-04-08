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
 * HTTP response class
 * 
 * Response resposibility to store state, headers, cookies and the content
 * of the answer to the current request. It's a simple value object.
 * 
 * @package    greebo
 * @subpackage essence
 * @author     blerou
 */
class HttpResponse
{
    private $_header = array();
    private $_cookie = array();
    private $_status = 200;
    private $_content;

    public function setStatus($status)
    {
        $this->_status = (int)$status;
    }

    public function getStatus()
    {
        return $this->_status;
    }
    
    public function setHeader($name, $value)
    {
        $this->_header[$name] = $value;
    }

    public function getHeaders()
    {
        return $this->_header;
    }
    
    /**
     * Setter of a cookie 
     *
     * Uses same API as setrawcookie() function.
     *
     * @see setrawcookie
     */
    public function setCookie()
    {
        $args = func_get_args();
        $this->_cookie[$args[0]] = $args;
    }

    public function getCookies()
    {
        return $this->_cookie;
    }
    
    /**
     * Setter of the response body
     *
     * @param  string $content
     */
    public function setContent($content)
    {
        $this->_content = (string)$content;
    }

    public function getContent()
    {
        return $this->_content;
    }
}