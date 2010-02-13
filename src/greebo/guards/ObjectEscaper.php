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
 * Escaper for an object variable.
 *
 * @package    greebo
 * @subpackage guards
 * @author     blerou
 */
class ObjectEscaper extends Escaper implements Countable
{
    /**
     * Object property getter.
     *
     * Return the escaped value of the property.
     *
     * @param  string $name
     * @return mixed
     */
    function __get($name)
    {
        return Escaper::escape($this->$value->$name, $this->escaper);
    }
    /**
     * Object method call.
     *
     * Return the escaped value of the property.
     *
     * @param  string $name
     * @return mixed
     */
    function __call($method, $args)
    {
        return Escaper::escape(call_user_func_array(array($this->value, $method), $args), $this->escaper);
    }

    /**
     * Object property count.
     *
     * @return int
     */
    function count()
    {
        return count($this->value);
    }
}
