<?php

namespace greebo\security;

class ObjectEscaper extends Escaper implements Countable
{
    function __get($name)
    {
        return Escaper::escape($this->$value->$name, $this->escaper);
    }

    function __call($method, $args)
    {
        return Escaper::escape(call_user_func_array(array($this->value, $method), $args), $this->escaper);
    }

    function count()
    {
        return count($this->value);
    }
}
