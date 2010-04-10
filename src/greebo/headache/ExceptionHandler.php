<?php

/**
 * This file is part of the greebo essence pack.
 *
 * Copyright (c) 2010 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace greebo\headache;

interface ExceptionHandler
{
    /**
     * @param  \Exception $exception
     * @return HttpResponse
     * @throws \Exception
     */
    public function handle(\Exception $exception);
}