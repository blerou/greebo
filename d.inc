<?php

class HandlerFor404
{
  const URI = '.*';
  
  function __construct()
  {
    $this->r = new Res;
    $r->header[] = sprintf('%s 404', $_SERVER['SERVER_PROTOCOL']);
  }
  
  function get()
  {
    $this->r->content = 'ooopss';
    return $this->r;
  }
  
  function post()
  {
    return $this->r;
  }
  
  function put()
  {
    return $this->r;
  }
  
  function head()
  {
    return $this->r;
  }
}
