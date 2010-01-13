<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

class Template extends \greebo\essence\Base
{
    private
        $_slots = array(),
        $_slot,
        $_escaper;

    function __get($slot)
    {
        return isset($this->_slots[$slot])
            ? $this->_slots[$slot]
            : null;
    }

    function __set($slot, $val)
    {
        $this->_slots[$slot] = $val;
    }

    function slot($slot)
    {
        $this->_slot = $slot;
        ob_start();
    }

    function stop()
    {
        $this->_slots[$this->_slot] = ob_get_clean();
        $this->_slot = null;
    }

    function escaper(\Closure $escaper)
    {
        $this->_escaper = $escaper;
    }

    function fetch()
    {
        $this->_slots = $this->container()->hooks->filter('template.slots', $this, $this->_slots);
        ob_start();
        $this->content();
        return ob_get_clean();
    }

    function content()
    {
    }

    function escape($val)
    {
        return is_string($val)
            ? call_user_func($this->_escaper, $val)
            : $val;
    }
}
