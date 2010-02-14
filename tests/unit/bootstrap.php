<?php

/*
 * This file is part of the greebo pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

require_once __DIR__.'/../../src/greebo/essence/Greebo.php';
\greebo\essence\Greebo::register();
\greebo\essence\Greebo::register(__DIR__.'/../lib');

require_once __DIR__.'/../lib/lime.php';
