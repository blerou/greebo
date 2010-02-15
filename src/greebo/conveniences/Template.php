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

class Template
{
    private
        $_slots = array(),
        $_raw = array(),
        $_slot,
        $_escaper,
        $_event;

    function __construct(\greebo\essence\Event $event)
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

        $this->_slots = $this->_event
            ->filter('template.slots', $this, $this->_slots);

        ob_start();

        $this->content();

        $content = trim(ob_get_clean());
       
        $r = new \ReflectionObject($this);
        $p = $r->getParentClass();
        if ($p && $p->name != 'greebo\\conveniences\\Template') {
            if ($content) {
                $this->assign('content', $content.$this->content);
            }
            $content = $p->newInstance($this->_event)
                ->assign($this->_raw)
                ->fetch();
        }

        return $content;
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