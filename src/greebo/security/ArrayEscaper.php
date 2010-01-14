<?php

namespace greebo\security;

class ArrayEscaper extends Escaper implements ArrayAccess, Countable, Iterator
{
    function key()
    {
        return key($this->value);
    }

    function current()
    {
        return Escaper::escape(current($this->value), $this->escaper);
    }

    function next()
    {
        next($this->value);
        $this->count --;
    }

    function valid()
    {
        return $this->count > 0;
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
