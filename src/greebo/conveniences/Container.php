<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

class Container extends \greebo\essence\Container
{
    function init()
    {
        parent::init();

        $this->vendor = 'greebo';
        $this->app = 'default';
        $this->charset = 'utf-8';
        $this->request = $this->shared(function($c) { return new Request; });
        $this->response = $this->shared(function($c) { return new Response($c); });
        $this->action_name = $this->request->param('action', 'index');
        $this->action = $this->shared(function($c) {
            $class = sprintf('%s\\%s\\ActionSet', $c->vendor, $c->app);
            return new $class($c);
        });
        $this->template = $this->shared(function($c) {
            $class = sprintf('%s\\%s\\Template\\%s', $c->vendor, $c->app, $c->action_name);
            if (!class_exists($class)) {
                $class = 'greebo\\conveniences\\Template\\error404';
            }
            $template = new $class($c);
            $template->escaper(function($val) use($c) {
                return htmlspecialchars($val, ENT_QUOTES, $c->charset);
            });
            return $template;
        });
    }
}
