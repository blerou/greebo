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

abstract class Escaper extends \greebo\essence\Base
{
    static private $_escapers = array(
        'array' => '\\greebo\\security\\ArrayEscaper',
        'object' => '\\greebo\\security\\ObjectEscaper',
    );

    protected 
        $value,
        $escaper;

    function __construct($value, \Closure $escaper)
    {
        $this->value = $value;
        $this->escaper = $escaper;
    }

    static function escape($slot, \Closure $escaper)
    {
        if (is_scalar($slot)) {
            return $escaper($slot);
        }
        $type = gettype($slot);
        if (!isset(self::$_escapers[$type])) {
            return $slot;
        }
        return new self::$_escapers[$type]($slot, $escaper);
    }

    static function register($container)
    {
        $container->event->connect('template.slots', function ($template, $slots) {
            foreach ($slots as $name => $slot) {
                if ($slot instanceof Escaper) {
                    continue;
                }
                $slots[$name] = Escaper::escape($slot, $template->escaper());
            }
            return $slots;
        });
    }
}
