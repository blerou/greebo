<?php

class Req
{
  function __call($method, $args)
  {
    $p = array();
    switch ($method)
    {
      case 'cookie':
        $p = $_COOKIE;
        break;
      case 'header':
        $p = $_SERVER;
        break;
      case 'file':
        $p = $_FILES;
        break;
      case 'param':
        $p = ${'_'.$this->method};
        break;
    }
    return isset($c[$args[0]]) ? $c[$args[0]] : $args[1];
  }
}

class Res
{
  public $header, $cookie;
  public $content;
  
  /**
   * send response
   */
  function send()
  {
    foreach ((array)$this->header as $header)
      header($header);
    foreach ((array)$this->cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    echo $this->content;
  }
}
