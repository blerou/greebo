<?php

/*
 * This file is part of the greebo security pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\security;

abstract class Bootstrap extends \greebo\conveniences\Bootstrap
{
    function __construct($env)
    {
        Escaper::register($this->container());

        parent::__construct($env);
    }
}
