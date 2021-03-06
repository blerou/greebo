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
 * Base escaper class
 *
 * Every escaper holds the wrapped value and the escaper closure.
 * It's possibly to change escaper in different contexts (html, javascript).
 *
 * It also provides a simple interface (::register() method) what
 * attach the automatic escaping to the template values filter.
 *
 * To enable autoescaping put the followings to the end of
 * init method of your app's Bootstrap class:
 * <code>
 *   \greebo\guards\Escaper::register($this->container()->event);
 * </code>
 *
 * @package    greebo
 * @subpackage guards
 * @author     blerou
 */
abstract class Escaper
{
    static private $_escapers = array(
        'array' => '\\greebo\\guards\\ArrayEscaper',
        'object' => '\\greebo\\guards\\ObjectEscaper',
    );

    /**
     * the wrapped value
     *
     * @var mixed
     */
    protected $value;

    /**
     * the escaper closure
     *
     * @var Closure
     */
    protected $escaper;

    /**
     * Constructor
     *
     * @param  mixed $value
     * @param  Closure $escaper
     */
    function __construct($value, \Closure $escaper)
    {
        $this->value = $value;
        $this->escaper = $escaper;
    }

    /**
     * Common escaper method
     *
     * It escapes the given value with the given escaper,
     * or create an according Escaper instance.
     *
     * @param  mixed $value
     * @param  Closure $escaper
     * @return mixed  The escaped value or an Escaper instance
     * @throws EscaperException
     */
    static function escape($value, \Closure $escaper)
    {
        if ($value instanceof Escaper) {
            return $value;
        }

        if (is_scalar($value)) {
            if (is_string($value)) {
                return $escaper($value);
            }
            return $value;
        }

        $type = gettype($value);
        if (!isset(self::$_escapers[$type])) {
            throw new EscaperException('Unescapeable type: '.$type);
        }
        
        return new self::$_escapers[$type]($value, $escaper);
    }

    /**
     * Register autoescaping on template variables.
     * 
     * @param \greebo\essence\Event $event
     */
    static function register(\greebo\essence\Event $event)
    {
        $event->connect('template.slots', function ($template, $slots) {
            foreach ($slots as $name => $slot) {
                $slots[$name] = Escaper::escape($slot, $template->escaper());
            }
            return $slots;
        });
    }
}

/**
 * General escaper exception
 *
 * It is strongly coupled to Escaper, so this is a cool place for it.
 *
 * @package    greebo
 * @subpackage guards
 * @author     blerou
 */
class EscaperException extends \Exception
{
}