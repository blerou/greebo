<?php

namespace greebo\conveniences;

class Response extends \greebo\essence\Response
{
  function init() {
    $this->header('Content-type', 'text/html; charset=utf8');
  }
  
  function redirect($uri) {
    $this->header('Location', $uri);
    $this->content = null;
    $this->send();
    exit;
  }
}