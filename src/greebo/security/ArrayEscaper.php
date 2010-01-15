<?php

/*
 * This file is part of the greebo security pack.
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

namespace greebo\security;

class ArrayEscaper extends Escaper implements \ArrayAccess, \Countable, \Iterator
{
    private $_count;

    function key()
    {
        return Escaper::escape(key($this->value), $this->escaper);
    }

    function current()
    {
        return Escaper::escape(current($this->value), $this->escaper);
    }

    function next()
    {
        next($this->value);
        $this->_count --;
    }

    function valid()
    {
        return ($this->_count > 0);
    }

    function rewind()
    {
        reset($this->value);
        $this->_count = count($this->value);
    }

    function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    function offsetGet ($offset)
    {
        return Escaper::escape($this->value[$offset], $this->escaper);
    }

    function offsetSet($offset, $value)
    {
        throw new EscaperException();
    }

    function offsetUnset($offset)
    {
        throw new EscaperException();
    }

    function count()
    {
        return count($this->value);
    }
}
