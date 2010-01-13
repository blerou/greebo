<?php

/*
 * This file is part of the greebo essence pack
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\essence;

class Base
{
    private $_container;

    function __construct(Container $container)
    {
        $this->_container = $container;
    }

    function container()
    {
        return $this->_container;
    }
}
