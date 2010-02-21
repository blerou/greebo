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

use greebo\essence\Event;

class Template
{
    private
        $_slots = array(),
        $_raw = array(),
        $_slot,
        $_escaper,
        $_event,
        $_extends;

    function __construct(Event $event)
    {
        $this->_event = $event;
    }

    function __get($slot)
    {
        return isset($this->_slots[$slot])
            ? $this->_slots[$slot]
            : null;
    }

    function __set($slot, $val)
    {
        $this->assign($slot, $val);
    }

    function __isset($slot)
    {
        return isset($this->_slots[$slot]);
    }

    function assign($slot, $val = null)
    {
        if (is_array($slot)) {
            foreach ($slot as $name => $val) {
                $this->assign($name, $val);
            }
        } else {
            $this->_slots[$slot] = $val;
            $this->_raw[$slot] = $val;
        }
        return $this;
    }

    function __unset($slot)
    {
        unset($this->_slots[$slot], $this->_raw[$slot]);
    }

    function raw($slot)
    {
        return isset($this->_raw[$slot])
            ? $this->_raw[$slot]
            : null;
    }

    function rec($slot)
    {
        $this->_slot = $slot;
        ob_start();
    }

    function stop()
    {
        $value = ob_get_clean();
        $this->assign($this->_slot, $value);
        $this->_slot = null;
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

        $this->_slots = $this->_event->filter('template.slots', $this, $this->_slots);

        ob_start();
        $this->content();
        $this->assign('_content', trim(ob_get_clean()));

        $extends = $this->_extends;
        if (null === $extends) {
            $r = new \ReflectionObject($this);
            $p = $r->getParentClass();
            if ($p && $p->name != 'greebo\\conveniences\\Template') {
                $extends = $p->name;
            }
        }
        
        return ($extends)
            ? $this->render($extends, $this->_raw)
            : $this->_content;
    }

    function setup()
    {
    }

    function content()
    {
    }

    function extend($class)
    {
        $this->_extends = $class;
    }

    function escape($val)
    {
        if (is_string($val)) {
            $escaper = $this->_escaper;
            return $escaper($val);
        }
        return $val;
    }

    /**
     * helper method to render arbitrary templates with the given variables
     *
     * these classes act as partials.
     *
     * @param  string $class
     * @param  array  $vars
     * @return string
     */
    function render($class, array $vars = array())
    {
        $template = new $class($this->_event);
        return $template->assign($vars)->fetch();
    }
}