<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

class Request extends \greebo\essence\Request
{
  function method($method)
  {
    return (strtoupper($method) === $_SERVER['REQUEST_METHOD']);
  }

  function ajax()
  {
    return ('XMLHttpRequest' === $_SERVER['HTTP_X_REQUESTED_WITH']);
  }
}
