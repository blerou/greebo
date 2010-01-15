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
