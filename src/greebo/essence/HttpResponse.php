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
 * Simple response class
 * 
 * Response resposibility to store state, headers, cookies and the content
 * of the answer to the current request.
 * 
 * At the and of the dispatch process, the last step is to send response back to the client.
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