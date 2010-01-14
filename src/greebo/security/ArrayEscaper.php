<?php

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
