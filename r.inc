<?php

class Req
{
  function __call($method, $args)
  {
    switch ($method)
    {
      case 'param':
        $param = $GLOBALS['_'.$this->m];
        break;
      default:
        $param = @$GLOBALS['_'.strtoupper($method)];
    }
    return @$param[$args[0]] ? $param[$args[0]] : @$args[1];
  }
}

class Res
{
  function send()
  {
    foreach ((array)$this->header as $header)
      header($header);
    foreach ((array)$this->cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    echo $this->content;
  }
}
