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
 * HTTP response sender
 *
 * @package    greebo
 * @subpackage essence
 * @author     blerou
 */
class HttpResponder
{
    private $_protocol;

    public function __construct($protocol = 'HTTP/1.0')
    {
        $this->_protocol = $protocol;
    }

    public function send(HttpResponse $response)
    {
        header($this->_protocol.' '.$response->getStatus());
        foreach ($response->getHeaders() as $name => $header) {
            header("$name: $header");
        }
        foreach ($response->getCookies() as $name => $cookie) {
            call_user_func_array('setrawcookie', $cookie);
        }
        echo $response->getContent();
    }
}