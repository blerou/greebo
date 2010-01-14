<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

class Event 
{
    private $_events = array();
    
    function connect($event, $callable)
    {
        if (!isset($this->_events[$event])) {
            $this->_events[$event] = array();
        }
        $this->_events[$event][] = $callable;
    }
    
    function fire($event, $subject)
    {
        if (!isset($this->_events[$event])) {
            return;
        }
        foreach ($this->_events[$event] as $callable) {
            $result = $callable($subject);
            if (null !== $result) {
                return $result;
            }
        }
    }
    
    function filter($event, $subject, $content)
    {
        if (!isset($this->_events[$event])) {
            return $content;
        }
        foreach ($this->_events[$event] as $callable) {
            $content = $callable($subject, $content);
        }
        return $content;
    }
}
