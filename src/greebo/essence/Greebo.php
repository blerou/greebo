<?php

/*
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

class Greebo
{
    private $_container;
    
    function __construct($container) {
        $this->_container = $container;
    }
    
    function unleash() {
        $container = $this->_container;

        $container->hooks->fire('startup', $container);
        
        if (!is_callable($action = $container->action)) {
            $action = function($c) {
                $c->response->status(404);
                $c->response->content('error404');
            };
        }

        $container->hooks->fire('preexec', $container);
        
        $action($container);
        
        $container->hooks->fire('postexec', $container);
        
        $container->response->send();
        
        $container->hooks->fire('shutdown', $container);
    }

    static function register($base_dir = null)
    {
        if (null === $base_dir) {
            $base_dir = realpath(dirname(__DIR__).'/../');
        }
        spl_autoload_register(function($class) use($base_dir) {
            $class = str_replace('\\', '/', $class.'.php');
            $file = rtrim($base_dir, '/').'/'.ltrim($class, '/');
            if (file_exists($file)) {
                require $file;
            }
        });
    }
}
