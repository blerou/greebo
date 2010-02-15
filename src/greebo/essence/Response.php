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
 *
 * @property array       $_header   Store headers
 * @property array       $_cookie   Store cookies
 * @property int         $_status   Store the status code
 * @property string|null $_content  Stores the response body
 */
class Response
{
    private 
        $_header = array(),
        $_cookie = array(),
        $_status = 200,
        $_content = null,
        $_event;

    function __construct(Event $event)
    {
        $this->_event = $event;
    }

    /**
     * Setter of status code
     *
     * @param  int $status
     * @return \greebo\essence\Response
     */
    function status($status)
    {
        $this->_status = (int)$status;
        return $this;
    }
    
    /**
     * Setter of a header
     *
     * @param  string $name
     * @param  string $val
     * @return \greebo\essence\Response
     */
    function header($name, $val)
    {
        $this->_header[$name] = $val;
        return $this;
    }
    
    /**
     * Setter of a cookie 
     *
     * Uses same API as setrawcookie() function.
     *
     * @see setrawcookie
     * @return \greebo\essence\Response
     */
    function cookie()
    {
        $this->_cookie[] = func_get_args();
        return $this;
    }
    
    /**
     * Setter of the response body
     *
     * @param  string $content
     * @return \greebo\essence\Response
     */
    function content($content)
    {
        $this->_content = $content;
        return $this;
    }
    
    /**
     * Send response to client
     *
     * This is the very last method that called in dispatch process.
     */
    function send()
    {
        header($_SERVER['SERVER_PROTOCOL'].' '.$this->_status);
        foreach ($this->_header as $name => $header) {
            header("$name: $header");
        }
        foreach ($this->_cookie as $cookie) {
            call_user_func_array('setrawcookie', $cookie);
        }
        if (null !== $this->_content) {
            echo $this->_event->filter('response.content', $this, $this->_content);
        }
    }
}