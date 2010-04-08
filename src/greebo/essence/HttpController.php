<?php

/**
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) 2010 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace greebo\essence;

class HttpController implements Controller
{
    protected $action;

    /**
     * @param  HttpRequest $request
     * @return HttpResponse
     */
    public function handle($request)
    {
        $this->action = $request->action;
        $this->checkAction();
        return call_user_func(array($this, $this->action), $request);
    }

    private function checkAction()
    {
        if (!method_exists($this, $this->action)) {
            $message = sprintf('No action found: %s/%s', get_class($this), $this->action);
            throw new HttpException($message);
        }
    }
}