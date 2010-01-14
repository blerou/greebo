<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

class ActionSet extends \greebo\essence\Base
{
    private $_vars = array();
    
    function __invoke($container)
    {
        if (!method_exists($this, $method = $container->action_name.'Action')) {
            $container->action_name = 'error404';
            $method = 'error404Action';
        }

        if (false !== $this->$method() && $container->template) {
            $action = $this;
            $container->event->connect('template.slots', function($template, $slots) use($action) {
                foreach ($action->assigned() as $name => $val) {
                    $slots[$name] = $val;
                }
                return $slots;
            });
            $container->response->content($container->template->fetch());
        }
    }
    

    function error404Action()
    {
        $this->response->status(404);
        $this->response->content('Error 404 Page');
        return false;
    }

    function assign($slot, $val)
    {
        $this->_vars[$slot] = $val;
    }

    function assigned()
    {
        return $this->_vars;
    }
    
    function sendback($val)
    {
        $this->response->content($val);
        return false;
    }

    function __get($name)
    {
        return @$this->container()->$name ?: null;
    }

    function __set($name, $val)
    {
        $this->container()->$name = $val;
    }
}
