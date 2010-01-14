<?php

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
