<?php

namespace greebo\essence;

class Response extends Base
{
  private 
    $_header = array(),
    $_cookie = array(),
    $_status = 200,
    $_content = null;
  
  function status($status) {
    $this->_status = (int)$status;
  }
  
  function header($name, $val) {
    $this->_header[$name] = $val;
  }
  
  function cookie($val) {
    $this->_cookie[] = $val;
  }
  
  function content($content) {
    $this->_content = $content;
  }
  
  function send() {
    header($_SERVER['SERVER_PROTOCOL'].' '.$this->_status);
    foreach ($this->_header as $name => $header)
      header("$name: $header");
    foreach ($this->_cookie as $cookie)
      call_user_func_array('setrawcookie', $cookie);
    if (null !== $this->_content)
      echo $this->hooks->filter('response.content', $this, $this->_content);
  }

  function __get($name)
  {
    return @$this->container()->$name ?: null;
  }
}