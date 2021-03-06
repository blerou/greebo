<?php

/*
 * This file is part of the greebo conveniences pack.
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

namespace greebo\conveniences;

/**
 * Container with convenient predefined properties
 *
 * @property string $vendor
 * @property string $app
 * @property string $charset
 * @property boolean $debug
 * @property string $action_name
 *
 * @property \greebo\essence\Event $event
 * @property \greebo\conveniences\Request $request
 * @property \greebo\conveniences\Response $response
 * @property \greebo\conveniences\ActionSet $action
 * @property \greebo\conveniences\Template $template
 */
class Container extends \greebo\essence\Container
{
    function init()
    {
        $this->vendor = 'greebo';
        $this->app = 'conveniences';
        $this->charset = 'utf-8';
        $this->debug = false;

        $this->event = $this->shared(function($c) { return new \greebo\essence\Event; });
        $this->request = $this->shared(function($c) { return new Request; });
        $this->response = $this->shared(function($c) { return new Response($c->event, $c->charset); });

        $this->action_name = $this->request->param('action', 'index');
        $this->action = $this->shared(function($c) {
            $class = sprintf('%s\\%s\\ActionSet', $c->vendor, $c->app);
            return new $class($c);
        });

        $this->template = $this->shared(function($c) {
            $class = sprintf('%s\\%s\\Template\\%s', $c->vendor, $c->app, $c->action_name);
            if (!class_exists($class)) {
                throw new ContainerException;
            }
            $template = new $class($c->event);
            $template->escaper(function($val) use($c) {
                return htmlspecialchars($val, ENT_QUOTES, $c->charset);
            });
            return $template;
        });
    }
}

class ContainerException extends \Exception
{
}
