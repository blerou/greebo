<?php

/*
 * This file is part of the greebo conveniences pack.
 *
 * Copyright (c) Szabolcs Sulik <sulik.szabolcs@gmail.com>
 */

namespace greebo\conveniences;

class Response extends \greebo\essence\Response
{
  function init()
  {
    $this->header('Content-type', 'text/html; charset='.$this->container()->charset);
  }
  
  function redirect($uri)
  {
    $this->header('Location', $uri);
    $this->content = null;
    $this->send();
    exit;
  }
}
