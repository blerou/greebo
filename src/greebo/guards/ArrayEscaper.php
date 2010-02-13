<?php

/*
 * This file is part of the greebo guards pack.
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

namespace greebo\guards;

/**
 * Escaper for an array variable.
 *
 * @package    greebo
 * @subpackage guards
 * @author     blerou
 */
class ArrayEscaper extends Escaper implements \ArrayAccess, \Countable, \Iterator
{
    /**
     * hold the length of array
     * 
     * @var int
     */
    private $_count;

    /**
     * Current element's key getter
     *
     * @see Iterator
     *
     * @return mixed
     */
    function key()
    {
        return Escaper::escape(key($this->value), $this->escaper);
    }

    /**
     * Current element's value getter
     *
     * @see Iterator
     *
     * @return mixed
     */
    function current()
    {
        return Escaper::escape(current($this->value), $this->escaper);
    }

    /**
     * Go to the next element in the array
     *
     * @see Iterator
     */
    function next()
    {
        next($this->value);
        $this->_count --;
    }

    /**
     * Check the current element
     *
     * @see Iterator
     *
     * @return bool
     */
    function valid()
    {
        return ($this->_count > 0);
    }

    /**
     * Reset the array
     *
     * @see Iterator
     */
    function rewind()
    {
        reset($this->value);
        $this->_count = count($this->value);
    }

    /**
     * Check the given offset in the array
     *
     * @see ArrayAccess
     *
     * @param  mixed $offset
     * @return bool
     */
    function offsetExists($offset)
    {
        return isset($this->value[$offset]);
    }

    /**
     * Return the escaped value on the given offset
     *
     * @see ArrayAccess
     *
     * @param  mixed $offset
     * @return mixed
     */
    function offsetGet ($offset)
    {
        return Escaper::escape($this->value[$offset], $this->escaper);
    }

    /**
     * Array value setter.
     *
     * Escapers are immutable.
     *
     * @see ArrayAccess
     *
     * @throws EscaperException
     */
    function offsetSet($offset, $value)
    {
        throw new EscaperException();
    }

    /**
     * Array offset remover.
     *
     * Escapers are immutable.
     *
     * @see ArrayAccess
     *
     * @throws EscaperException
     */
    function offsetUnset($offset)
    {
        throw new EscaperException();
    }

    /**
     * return with the length of the array
     *
     * @see Countable
     *
     * @return int
     */
    function count()
    {
        return count($this->value);
    }
}
