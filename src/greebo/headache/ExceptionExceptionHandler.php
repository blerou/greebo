<?php

/**
 * This file is part of the greebo headache pack.
 *
 * Copyright (c) 2010 Szabolcs Sulik <sulik.szabolcs@gmail.com>
 *
 * @license http://www.opensource.org/licenses/mit-license.php
 */

namespace greebo\headache;

class ExceptionExceptionHandler implements ExceptionHandler
{
    public function handle(\Exception $exception)
    {
        throw $exception;
    }
}