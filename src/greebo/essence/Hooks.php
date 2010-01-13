<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

class Hooks
{
    private $_hooks = array();
    
    function reg($hook, $callable)
    {
        if (!isset($this->_hooks[$hook])) {
            $this->_hooks[$hook] = array();
        }
        $this->_hooks[$hook][] = $callable;
    }
    
    function fire($hook, $subject)
    {
        if (!isset($this->_hooks[$hook])) {
            return;
        }
        foreach ($this->_hooks[$hook] as $callable) {
            $result = $callable($subject);
            if (null !== $result) {
                return $result;
            }
        }
    }
    
    function filter($hook, $subject, $content)
    {
        if (!isset($this->_hooks[$hook])) {
            return $content;
        }
        foreach ($this->_hooks[$hook] as $callable) {
            $content = $callable($subject, $content);
        }
        return $content;
    }
}
