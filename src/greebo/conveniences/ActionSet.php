<?php

/*
 * This file is part of the greebo conveniences pack.
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

namespace greebo\conveniences;

class ActionSet
{
    private $_vars = array();
    private $_container;
    
    function __invoke($container)
    {
        $this->_container = $container;

        $method = $container->action_name.'Action';
        if (!method_exists($this, $method)) {
            $container->action_name = 'error404';
            $method = 'error404Action';
        }

        if (false === $this->$method()) {
            return;
        }

        $template = $container->template;

        if (!$template) {
            return;
        }

        foreach ($this->_vars as $name => $value) {
            $template->$name = $value;
        }
        $container->response->content($template->fetch());
    }
    

    function error404Action()
    {
        $this->response->status(404);
        $this->response->content('Error 404 Page');
        return false;
    }

    function assign($var, $value)
    {
        $this->_vars[$var] = $value;
    }

    function __get($name)
    {
        return $this->_container->$name;
    }

    function __set($name, $val)
    {
        $this->_container->$name = $val;
    }
}
