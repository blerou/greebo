<?php

/*
 * This file is part of the greebo essence pack.
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

namespace greebo\essence;

/**
 * Main dispatcher class
 * 
 * Greebo's resposibility is to dispatch the request through its ->unleash() method.
 * It fires some event at different states of dispatch process.
 * 
 * Used event:
 *  - greebo.startup: event fired at the begining of the process
 *  - greebo.preexec: event fired just before action execution
 *  - greebo.postexec: event fired just after action execution
 *  - greebo.shutdown: event fired at the end of the process
 *
 * Every greebo prefixed event takes the global container as subject.
 * 
 * It also provides a simple autoloader in its static ::register() method.
 * 
 * @package    greebo
 * @subpackage essence
 * @author     blerou
 */
class Greebo
{
    private $_container;
    
    function __construct($container) {
        $this->_container = $container;
    }
    
    /**
     * Dispatch the request
     */
    function unleash() {
        $container = $this->_container;

        $container->event->fire('greebo.startup', $container);
        
        if (!is_callable($action = $container->action)) {
            $action = function($c) {
                $c->response->status(404);
            };
        }

        $container->event->fire('greebo.preexec', $container);
        
        $action($container);
        
        $container->event->fire('greebo.postexec', $container);
        
        $container->response->send();
        
        $container->event->fire('greebo.shutdown', $container);
    }

    /**
     * Register autoload callback
     *
     * It tries to load the classes under given base directory
     * If base dir is not given, it registers greebo.
     *
     * @param  string $base_dir
     * @return null
     */
    static function register($base_dir = null)
    {
        if (null === $base_dir) {
            $base_dir = realpath(__DIR__.'/../../');
        }
        $base_dir = rtrim($base_dir, '/').'/';

        spl_autoload_register(function($class) use($base_dir) {
            $class = str_replace('\\', '/', $class.'.php');
            $file = $base_dir.ltrim($class, '/');
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
