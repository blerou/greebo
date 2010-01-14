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
        $_raw = array(),
        $_slot,
        $_decorator,
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
        $this->_raw[$slot] = $val;
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

    function extend($class)
    {
      $this->_decorator = $class;
    }

    function escaper(\Closure $escaper = null)
    {
        if (null === $escaper) {
            return $this->_escaper;
        }
        $this->_escaper = $escaper;
    }

    function fetch()
    {
        $this->setup();

        $this->_slots = $this->container()->event
            ->filter('template.slots', $this, $this->_slots);

        ob_start();

        $this->content();
        
        if (null !== $this->_decorator) {
            $container = $this->container();
            $class = sprintf(
                '\\%s\\%s\\Template\\%s', 
                $container->vendor, 
                $container->app,
                $this->_decorator
            );
            $decorator = new $class($this->container());
            foreach ($this->_slots as $name => $val) {
                $decorator->$name = $val;
            }
            echo $decorator->fetch();
        }

        return ob_get_clean();
    }

    function setup()
    {
    }

    function content()
    {
    }

    function escape($val)
    {
        if (is_string($val)) {
            $escaper = $this->_escaper;
            return $escaper($val);
        }
        return $val;
    }

    function raw($slot)
    {
        return isset($this->_raw[$slot])
            ? $this->_raw[$slot]
            : null;
    }
}
